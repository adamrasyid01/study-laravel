<x-app-layout>
    <h1>Tasks</h1>

    <form action="/tasks" method="post">
        @csrf
        <input type="text" name="list" placeholder="The Name of the Task">
        <button type="submit">
            Add
        </button>
    </form>
  
    <ul style="list-style-type: none;">
        @foreach ($tasks as $index =>  $task)
        <li>{{ $index + 1 }} - {{$task->list}}</li>
            
        @endforeach
    </ul>
   
</x-app-layout>