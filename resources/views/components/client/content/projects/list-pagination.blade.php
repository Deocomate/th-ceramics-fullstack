@props(['projects'])
<x-client.shared.rounded-pagination :paginator="$projects->withQueryString()" />
