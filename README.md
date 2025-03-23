# Laravel Routing, Blade, dan Controller

## 1. Routing di Laravel
### a. Struktur Folder dalam Routing
Jika menggunakan titik dalam route, maka akan masuk ke dalam folder:
```php
Route::get('/', function () {
    return view('1.2.3.tes');
});
```

### b. Passing Variabel ke Route
Untuk mengirim variabel ke view:
```php
Route::get('profile', function () {
    $name = 'James';
    return view('profile', ['name' => $name]);
});
```
Bisa juga menggunakan `compact()`:
```php
Route::get('profile', function () {
    $name = 'James';
    return view('profile', compact('name'));
});
```

---
## 2. Asset Management
### a. Menyertakan CSS dan JS di Blade
Simpan file CSS dan JS di folder `public`, lalu sertakan di dalam Blade:
```html
<link rel="stylesheet" href="/css/app.css">
```
Best practice menggunakan `asset()`:
```html
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
```

---
## 3. Blade Templating Engine
### a. Menggunakan `@include`
Digunakan untuk menghindari duplikasi kode:
```php
@include('header')
```

### b. Menggunakan `@yield`
Gunakan di parent untuk membuat konten dinamis:
```php
@yield('content')
```
Di child component:
```php
@section('content')
    <p>Isi Konten</p>
@endsection
```
Cara lain:
```php
@extends('layouts.app', ['title' => 'HomePage'])
```

---
## 4. Blade Components
`@yield` dan `@include` dapat diganti dengan Blade Component.

### a. Contoh Component
Definisikan komponen:
```php
<div class="alert">
    <div class="alert-header">
        {{ $title }}
    </div>
    {{ $slot }}
</div>
```

Gunakan di Blade:
```php
<x-navbar></x-navbar>
```
Buat component dengan Artisan:
```sh
php artisan make:component Alert
```
File component akan ada di `app/View/Components/`.

Jika ingin mengubah tampilan yang dirender (layout), ubah fungsi `render()` dalam component.

---
## 5. Layout dengan Component
Jika variabel dinamis hanya berisi string, gunakan sebagai props:

**Parent (`Layouts/app.blade.php`)**:
```php
<title>{{ $title }} | DAMS</title>
{{ $slot }}
```

**Child**:
```php
<x-app-layout title="HomePage">
    HomePage
</x-app-layout>
```

### a. Mengatur Default Value di Constructor
Jika variabel dalam `__construct` bersifat wajib, buat opsional:
```php
public function __construct($title = null) {
    $this->title = $title ?? "DAMS";
}
```

---
## 6. Menambahkan Custom Style/Javascript di Page
Tambahkan di **Parent**:
```php
{{ $styles }}
```

Tambahkan variabel di component:
```php
public $styles = null;
```

Gunakan di **Child**:
```php
@slot('styles')
    <style>
        body { background-color: red; }
    </style>
@endslot
```

---
## 7. Passing Request dari URL ke View
### a. Menggunakan `Request`
```php
use Illuminate\Http\Request;
Route::get('profile', function (Request $request) {
    $name = $request->name;
    return "MY NAME IS {$name}";
});
```
Pemanggilan:
```php
<x-app-layout title="{{ $name ?? 'Profile' }}">
    <h1>{{ $name ?? 'Profile' }}</h1>
</x-app-layout>
```
Atau:
```php
<x-app-layout :title="$name ?? 'Profile'">
    <h1>{{ $name ?? 'Profile' }}</h1>
</x-app-layout>
```

### b. Menggunakan Wildcard
```php
Route::get('profile/{username}', function ($username) {
    return view('profile', ['name' => $username]);
});
```

---
## 8. Controller di Laravel
### a. Membuat Controller
Gunakan Artisan:
```sh
php artisan make:controller UserController
```
Nama controller harus menggunakan CamelCase (`UserController`).

### b. Memanggil Controller di Route
```php
Route::get('/', [UserController::class, 'index']);
```

Jika tidak ada aksi lain, gunakan `__invoke()`:
```php
public function __invoke() {
    return 'home';
}
```
Pemanggilan tanpa array:
```php
Route::get('/', HomeController::class);
```

### c. Method `POST`
Gunakan `Route::post` dan method `store()` di Controller:
```php
public function store() {
    dd('submitted');
}
```
Gunakan di Blade:
```html
<form action="/contact" method="post">
    @csrf
    <button type="submit">Send</button>
</form>
```

---
## 9. Passing Request ke Controller
### a. Route dengan Parameter
```php
Route::get('profile/{identifier}', [ProfileInformationController::class,'__invoke']);
```

### b. Controller
```php
public function __invoke($identifier) {
    return view('profile', compact('identifier'));
}
```

### c. View
```php
<x-app-layout title="{{ $identifier ?? 'Profile' }}">
    <h1>{{ $identifier ?? 'Profile' }}</h1>
</x-app-layout>
```

---
### ✅ **Selesai!** 🚀

