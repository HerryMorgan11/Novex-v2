<?php

use Livewire\Component;
use App\Models\User;

return new class extends Component
{
    public $section = "home";
    public $users = [];

    public function mount()
    {
        // Filtrar usuarios solo del tenant actual
        $tenantId = tenant()->id;
        $this->users = User::where('current_tenant_id', $tenantId)->get();
    }

    public function changeSection($section)
    {
        $this->section = $section;
    }

    protected function view($data = [])
    {
        return app('view')->file('/var/www/html/storage/tenant019cf7b4-bd23-72e2-bb02-c190d0be4c1b/framework/views/livewire/views/6185ef54.blade.php', $data);
    }
};

