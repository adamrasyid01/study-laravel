# Laravel Routing & Blade Templating

## 1. ROUTE

Route WEB kalau pakai titik artinya masuk ke folder:
```php
Route::get('/', function () {
    return view('1.2.3.tes');
});
```

Untuk passing variabel ke Route Web:
```php
Route::get('profile', function () {
    $name = 'James';
    return view('profile', ['name' => $name]);
});
```
Kalau Sama pakai `compact`

---

## 2. Bagaimana Dengan Assets

Include CSS dan JS ke Blade, taruh ke folder `public`.
Jika blade di dalam folder, maka untuk mengimpor asset harus ditambah `/` supaya mengakses main directory.
```html
<link rel="stylesheet" href="/css/app.css">
```
**Best Practice:**
```html
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
```

---

## 3. Blade Templating Engine

### 3.1 Include untuk membuat template supaya tidak berulang-ulang

### 3.2 Gunakan `yield` di parent supaya bisa diisi konten dinamis (seperti slot di Vue.js)
```blade
@yield('content')
```
Panggil di komponen anak:
```blade
@section('content', <<IsiKonten>>)
```
Cara lain:
```blade
{{ $variabel }}
@extends('layouts.app', ['title' => 'HomePage'])
```

---

## 4. Memahami Blade Component

`yield`, `include` bisa diganti menggunakan component.
**Syarat:** Harus di dalam folder `components`

Definisikan component yang ingin dinamis:
```blade
<div class="alert">
    <div class="alert-header">
        {{ $title }}
    </div>
    {{ $slot }}
</div>
```
Panggil dengan:
```blade
@component() @endcomponent
```
atau dengan anonymous component:
```blade
<x-navbar></x-navbar>
```

Cara membuat component:
```sh
php artisan make:component
```
Lalu buka folder `app/view`, maka akan ada file PHP hasil generate component.

Jika ingin mengubah file yang di-render karena itu adalah layout (BUKAN COMPONENT), buka `app/view` dan ganti fungsi `render()` dengan return folder yang sesuai.

---

## 5. Layout dengan Komponen

Jika variabel dinamis hanya berisi string bisa digunakan sebagai props saja:

**Folder Layouts/app.blade.php (Parent)**
```blade
<title>{{ $title }} | DAMS</title>
```
**Children**
```blade
<x-app-layout title="HomePage">
    HomePage
</x-app-layout>
```
Variabel diterima sebagai parameter di fungsi construct dalam `app/view/components`:
```php
public $title;
public function __construct($title){
    $this->title = $title;
}
```
Namun, tulisan `HomePage` di children tak bisa dibaca. Oleh karena itu, di parent tambahkan `{{ $slot }}` untuk membaca elemen yang ingin dimasukkan ke children-nya.

Variabel di dalam fungsi `__construct` bersifat required. Untuk membuatnya opsional, buatlah menjadi `null`.
Jika ingin ada default value-nya, buatlah tepat di constructor-nya:
```php
$this->title = $title ?? "DAMS";
```

---

## 6. Sisip Jika Diperlukan

Bagaimana satu page memiliki custom style/javascript?

Tambahkan di parent:
```blade
{{ $styles }}
```
**Parent (`app/view/components`)**
Tambahkan variabel di `app/view/components` untuk menangani-nya:
```php
public $title;
public $styles = null;
public function __construct($title = null){
    $this->title = $title ?? "DAMS";
}
```
Setelah itu, panggil di component anak:
```blade
@slot('styles')
    <style>
        body {
            background-color: red;
        }
    </style>
@endslot
```

---

## 7. Passing Request dari URL ke View (Request dan Wildcard)

**Request**
Gunakan `Request` (Import class `use Illuminate\Http\Request`):
```php
Route::get('profile', function (Request $request) {
    $name = $request->name;
    return "MY NAME IS {$name}";
});
```
Cara panggilnya:
```blade
<x-app-layout title="{{ $name ?? 'Profile' }}">
    <h1>{{ $name ?? 'Profile' }}</h1>
</x-app-layout>
```
Jika ingin menampilkan `echo` dengan component bisa menggunakan `:` (harus berupa props):
```blade
<x-app-layout :title="$name ?? 'Profile'">
    <h1>{{ $name ?? 'Profile' }}</h1>
</x-app-layout>
```

**Wildcard**
```php
Route::get('profile/{username}', function ($username) {
    return view('profile', ['name' => $username]);
});
```

---

## 8. Controller Pertama

Controller supaya kode bisa memaintain fungsi dari setiap aksi (POST, GET, DELETE).
Buat dengan command:
```sh
php artisan make:controller
```
Nama controller menggunakan CamelCase (contoh: `UserController`).

Untuk memanggil controller di route:
```php
Route::get('/', [UserController::class, 'index']);
```
Jika route tidak mempunyai action (hanya return view), gunakan fungsi `__invoke()` di controller-nya:
```php
public function __invoke()
{
    return 'home';
}
```
Cara memanggilnya tanpa array:
```php
Route::get('/', HomeController::class);
```
Untuk method `CREATE` gunakan `Route::post` dan fungsinya bernama `store`:
```php
public function store(){
    dd('submitted');
}
```
Tambahkan di view untuk menangani post request:
```blade
<form action="/contact" method="post">
    @csrf
    <button type="submit">Send</button>
</form>
```

---

## 9. Passing Request dengan Controller

**Di Route**
```php
Route::get('profile/{identifier}', [ProfileInformationController::class,'__invoke']);
```
**Di Controller**
```php
public function __invoke($identifier)
{
    return view('profile',compact('identifier'));
}
```
**Di View**
```blade
<x-app-layout title="{{ $identifier ?? 'Profile' }}">
    <h1>{{ $identifier ?? 'Profile' }}</h1>
</x-app-layout>
```

