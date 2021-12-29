<script defer>
    
    function photoPath(path) {
        @if (File::exists(Storage::url(path)))
            return {{Storage::url(path)}}
        @endif
        return "";        
    }
</script>