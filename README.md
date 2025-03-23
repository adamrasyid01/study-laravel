1. ROUTE

Route WEB kalau pakai titik artinya masuk ke folder

Route::get('/', function () {
    return view('1.2.3.tes');
});


Untuk passing variabel ke Route Web,seperti ini
Route::get('profile', function () {
    $name = 'James';
    return view('profile', ['name' => $name]);
});
Kalau Sama pakai compact

2. Bagaimana Dengan Assets
Include Css dan Js ke Blade
Taruh ke folder Public 

Jika blade didalam folder, maka untuk mengimpor asset harus ditambah "/" supaya mengakses main directory

<link rel="stylesheet" href="/css/app.css">

BEST PRACTICE : href = "{{asset(css/app.css)}}"

3.Blade Templating Engine

3.1 Include untuk membuat template supaya tidak berulang ulang

3.2 Gunakan Yield di parent supaya bisa diisi konten dinamis (seperti slot di vue.js)
@yield('content')

PANGGIL di komponen anak: @section('content', <<IsiKonten>>)

Cara Lain : Gunakan {{$variabel}} dan tambahkan ini @extends('layouts.app', ['title' => 'HomePage'])

4. MEMAHAMI BLADE COMPONENT

yield,include bisa diganti menggunakan component

SYARAT : HARUS DI DALAM FOLDER COMPONENTS
Definisikan component yang ingin dinamis dengan seperti ini:
<div class="alert">
    <div class="alert-header">
        {{ $title }}
    </div>
    {{ $slot }}
</div>

Lalu panggil dengan menggunakan @component() @endcomponent atau dengan anonymous component 
CONTOH : <x-navbar><x-navbar>


Cara membuat component : php artisan make:component
Lalu buka folder app/view maka akan ada file php hasil generate component

Jika ingin mengubah file yang di render karena itu adalah layout (BUKAN COMPONENT) maka buka app/view dan ganti fungsi render() dengan return folder yang sesuai

5. Layout dengan Komponent

Jika variabel dinamis hanya berisi string bisa digunakan sebagai props saja seperti ini

Folder Layouts/app.blade.php (Parent)

<title>{{ $title }} | DAMS</title>

Children
<x-app-layout title = "HomePage">
    HomePage
</x-app-layout>

Variabel diterima sebegai parameter di fungsi construct dalam folder app/view/components

public $title;
public function __construct($title){
    $this-> title = $title;
}

NAMUN TULISAN "HomePage" di children tak bisa dibaca, Oleh karena itu di parent tambahkan {{$slot}} untuk membaca element yang ingin dimasukkan ke childrennya

Variabel didalam fungsi _construct bersifat required, untuk membuatnya menjadi OPSIONAL-> Buatlah menjadi null 
Jika ingin ada default valuenya buatlah tepat di constructor nya
$this-> title = $title ?? "DAMS";

6. Sisip Jika Diperlukan 
Bagaimana satu page memiliki custom style/javascript

- TAMBAHKAN DI PARENT
{{ $styles }}

- PARENT (app/view/components)
Tambahkan variabel di app/view/components untuk menangani nya
    public $title;
    public $styles = null;
    public function __construct($title = null){
        //
        $this-> title = $title ?? "DAMS";
    }

- SETELAH ITU panggil di component anak
@slot('styles')
        <style>
            body{
                background-color: red;
            }
        </style>
@endslot

7. Passing request dari url ke view (Request dan Wildcard)

Bisa ga dari url (name =adam) tampilkan adam ke viewnya
- Request 
Gunakan yang namanya Request (Import class use Illuminate\Http\Request)
Route::get('profile', function  (Request $request) {
    $name = $request->name;
    return "MY NAME IS {$name}";
});

Cara panggilnya
<x-app-layout title="{{ $name ?? 'Profile' }}">
    <h1>{{$name ?? 'Profile'}}</h1>
</x-app-layout>
Jika ingin menampilkan echo dengan component bisa menggunakan ":" (harus berupa props)
ATAU
<x-app-layout :title="$name ?? 'Profile'">
    <h1>{{$name ?? 'Profile'}}</h1>
</x-app-layout>

- Wildcard
Route::get('profile/{username}', function  ($username) {

    return view('profile', ['name' => $username]);
});

8. Controller pertama
- Controller supaya kode bisa memaintain fungsi dari setiap aksi (POST,GET,DELETE)
Buat dengan command : php artisan make:controller
Nama controller menggunakan camel case contoh : (UserController)

- Untuk memanggil controller di route caranya :
Route::get('/', [UserController::class, 'index']);

- Jika route tidak mempunyai action -> hanya return view gunakan fungsi __invoke di controllernya()
public function __invoke()
{
    return 'home';
}
Cara memanggilnya tidak perlu array : Route::get('/', HomeController::class);

untuk method CREATE gunakan Route::post dan fungsinya bernama store
public function store(){
    dd('submitted');
}

Tambahkan di view untuk menangani post request
Contoh:
<form action="/contact" method="post">
    @csrf
    <button type="submit">Send</button>
</form>

9. Passing Request dengan Controller
- Di Route
Route::get('profile/{identifier}', [ProfileInformationController::class,'__invoke']);
- Di controller
public function __invoke($identifier)
{
    return view('profile',compact('identifier'));
}
- Di View
<x-app-layout title="{{ $identifier ?? 'Profile' }}">
    <h1>{{$identifier ?? 'Profile'}}</h1>
</x-app-layout>