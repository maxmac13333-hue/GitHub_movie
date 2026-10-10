<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeCinemaAdmin extends Command
{
    protected $signature = 'cinema:make-admin {email} {--reset-password : ตั้งรหัสผ่านใหม่โดยพิมพ์แบบไม่แสดงบนหน้าจอ}';
    protected $description = 'Grant cinema administrator access to an existing account';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('ไม่พบบัญชีอีเมลนี้ กรุณาสมัครสมาชิกก่อน');
            return self::FAILURE;
        }

        if ($this->option('reset-password')) {
            $password = $this->secret('รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)');
            $confirmation = $this->secret('ยืนยันรหัสผ่านใหม่');
            if (! is_string($password) || strlen($password) < 8 || $password !== $confirmation) {
                $this->error('รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษรและทั้งสองครั้งต้องตรงกัน');
                return self::FAILURE;
            }
            $user->password = $password;
        }

        $user->is_admin = true;
        $user->save();
        $this->info('ตั้งบัญชี '.$user->email.' เป็นผู้ดูแลแล้ว');
        return self::SUCCESS;
    }
}
