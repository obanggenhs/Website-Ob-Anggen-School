<?php

namespace App\Models;

use App\Support\Modules;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROLES = [
        'master'      => 'Master Admin',
        'publikasi'   => 'Admin Publikasi',
        'pendaftaran' => 'Admin Pendaftaran',
    ];

    protected $fillable = ['name', 'email', 'phone', 'role', 'avatar', 'is_active', 'last_login_at', 'created_by', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected ?array $permissionCache = null;

    protected ?Collection $assignedCache = null;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'is_active'         => 'boolean',
            'password'          => 'hashed',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isMaster(): bool
    {
        return $this->role === 'master';
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? ucfirst($this->role);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? media_url($this->avatar) : null;
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));

        return strtoupper(mb_substr($parts[0] ?? 'A', 0, 1).mb_substr($parts[1] ?? '', 0, 1));
    }

    /** Menu/submenu yang ditugaskan Master Admin ke role user ini. */
    public function assignedMenus(): Collection
    {
        if ($this->isMaster() || ! in_array($this->role, ['publikasi', 'pendaftaran'], true)) {
            return new Collection();
        }

        return $this->assignedCache ??= Menu::where('access_'.$this->role, true)
            ->orderBy('parent_id')->orderBy('position')->get();
    }

    /** Daftar modul dashboard yang boleh dikelola: bawaan role + dari menu yang ditugaskan. */
    public function permissions(): array
    {
        if ($this->permissionCache !== null) {
            return $this->permissionCache;
        }

        $keys = Modules::corePermissions($this->role);

        foreach ($this->assignedMenus() as $menu) {
            if ($key = Modules::permissionForMenu($menu->type, $menu->param)) {
                $keys[] = $key;
            }
        }

        return $this->permissionCache = array_values(array_unique($keys));
    }

    public function canManage(string $permission): bool
    {
        return $this->isMaster() || in_array($permission, $this->permissions(), true);
    }

    /** Boleh mengubah isi sebuah menu/halaman (blok, intro, banner). */
    public function canEditMenu(Menu $menu): bool
    {
        return $this->isMaster() || (bool) $menu->{'access_'.$this->role};
    }
}
