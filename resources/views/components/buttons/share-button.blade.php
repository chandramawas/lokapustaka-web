@props(['url', 'title' => 'Lokapustaka'])

<x-buttons.icon-button href="javascript:void(0);" onclick="shareBook('{{ $url }}')" variant="outline">
    <x-icons.share />
</x-buttons.icon-button>

@once
    @push('scripts')
        <script>
            function shareBook(url) {
                if (navigator.share) {
                    navigator.share({
                        title: @js($title),
                        url: url
                    }).then(() => {
                        console.log('Shared successfully');
                    }).catch((error) => {
                        console.error('Error sharing:', error);
                        alert('Gagal membagikan link.');
                    });
                } else {
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Link buku berhasil disalin ke clipboard.');
                    }).catch(err => {
                        console.error('Gagal salin:', err);
                    });
                }
            }
        </script>
    @endpush
@endonce