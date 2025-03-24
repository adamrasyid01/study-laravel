

10. Konfigurasi Tabel Pertama kita

Di folder config file database.php kita bisa set database yang diinginkan (MySQL,Postgres,dll)
Untuk menyesuaikan dengan tabel coba pergi ke file .env dan sesuaikan dengan nama tabel kamu :v

Untuk mengambil semua data yang ada di dab cara nya
public function index(){
    $tasks = DB::table('tasks')->get();
    dd($tasks);
}

Fetching ke view Begini caranya

TaskController.php
return view('tasks.index' , [
            'tasks' =>  DB::table('tasks')->get(),
]);

Blade
<ol>
    @foreach ($tasks as $task)
    <li>{{$task->list}}</li>
            
    @endforeach
</ol>

11. Masukkan data dalam tabel

Untuk memasukkan data ke tabel gunakan method post untuk route nya
routes/web.php
Route::post('tasks', [TaskController::class, 'store']); 

Controller
public function store(Request $request){
    DB::table('tasks')->insert([
            'list' => $request->list,
    ]);
}
untuk mengurutkan dari task yang paling akhir dimasukkan, coba dengan ini
public function index(){
    return view('tasks.index' , [
            'tasks' =>  DB::table('tasks')->orderBy('id', 'desc')->get(),
    ]);
}