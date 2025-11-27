<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite; // Thư viện Socialite

class AuthController extends Controller
{
    // --- ĐĂNG KÝ (THƯỜNG) ---
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/'
            ],
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất: 1 chữ thường, 1 chữ HOA, 1 số và 1 ký tự đặc biệt (@$!%*?&#)',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        // Tạo user mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Khởi tạo dữ liệu mặc định (Ví & Danh mục)
        $this->createDefaultData($user);

        // Đăng nhập luôn sau khi đăng ký
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Đăng ký thành công! Chào mừng bạn.');
    }

    // --- ĐĂNG NHẬP (THƯỜNG) ---
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Kiểm tra trạng thái tài khoản
        $user = User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Nếu là admin thì chuyển hướng vào trang admin
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    // --- ĐĂNG XUẤT ---
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ==========================================
    // GOOGLE LOGIN SECTION
    // ==========================================

    // 1. Chuyển hướng sang Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Xử lý khi Google trả về (Callback)
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Đăng nhập Google thất bại, vui lòng thử lại.']);
        }

        // Kiểm tra user đã tồn tại chưa (theo google_id hoặc email)
        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

        if ($user) {
            // --- TRƯỜNG HỢP USER ĐÃ TỒN TẠI ---
            
            // Kiểm tra trạng thái
            if (!$user->is_active) {
                return redirect()->route('login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
            }

            // Cập nhật google_id nếu chưa có (ví dụ trước đây đăng ký bằng email thường)
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }
            
            // Cập nhật avatar nếu chưa có
            if (!$user->avatar && $googleUser->avatar) {
                $user->update(['avatar' => $googleUser->avatar]);
            }

            Auth::login($user);
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        } else {
            // --- TRƯỜNG HỢP USER MỚI (CHƯA CÓ TÀI KHOẢN) ---
            
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => null, // MySQL cho phép null (vì đã sửa migration)
                'email_verified_at' => now(), // Google đã xác thực email
                'avatar' => $googleUser->avatar
            ]);

            // QUAN TRỌNG: Tạo dữ liệu mặc định cho user Google mới
            $this->createDefaultData($newUser);

            Auth::login($newUser);
            return redirect()->route('dashboard')->with('success', 'Chào mừng thành viên mới từ Google!');
        }
    }

    // --- HÀM PHỤ: TẠO DỮ LIỆU MẶC ĐỊNH ---
    // Tách ra hàm riêng để dùng chung cho cả Register thường và Google Login
    private function createDefaultData($user)
    {
        $defaultCategories = [
            [
                'name' => 'Ăn uống', 
                'type' => 'expense', 
                'icon' => 'ri-restaurant-2-line',
                'color' => '#EF4444'
            ],
            [
                'name' => 'Di chuyển', 
                'type' => 'expense', 
                'icon' => 'ri-car-line', 
                'color' => '#3B82F6'
            ],
            [
                'name' => 'Tiền nhà', 
                'type' => 'expense', 
                'icon' => 'ri-home-4-line', 
                'color' => '#F59E0B'
            ],
            [
                'name' => 'Giải trí', 
                'type' => 'expense', 
                'icon' => 'ri-gamepad-line', 
                'color' => '#8B5CF6'
            ],
            [
                'name' => 'Lương', 
                'type' => 'income', 
                'icon' => 'ri-wallet-3-line',
                'color' => '#10B981'
            ],
            [
                'name' => 'Thưởng', 
                'type' => 'income', 
                'icon' => 'ri-medal-line', 
                'color' => '#06B6D4'
            ],
        ];

        foreach ($defaultCategories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name' => $cat['name'],
                'type' => $cat['type'],
                'icon' => $cat['icon'],
                'color' => $cat['color'],
            ]);
        }

        Wallet::create([
            'user_id' => $user->id,
            'name' => 'Tiền mặt',
            'balance' => 0,
            'icon' => 'ri-wallet-3-line',
            'color' => '#10B981'
        ]);
    }
}