@props(['book' => null])

<form action="{{ route('book.bookmark', $book->slug ?? '#') }}" method="post">
    @csrf
    @if(auth()->user()->savedBooks->contains($book->id ?? '#'))
        <x-buttons.icon-button type="submit" variant="secondary">
            <x-icons.bookmark variant="remove" />
        </x-buttons.icon-button>
    @else
        <x-buttons.icon-button type="submit" variant="outline">
            <x-icons.bookmark variant="add" />
        </x-buttons.icon-button>
    @endif
</form>