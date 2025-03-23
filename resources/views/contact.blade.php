<x-app-layout title="Contact Page">
    <h1>Contact Page</h1>

    <form action="/contact" method="post">
        @csrf
        <button type="submit">Send</button>
    </form>
</x-app-layout>
