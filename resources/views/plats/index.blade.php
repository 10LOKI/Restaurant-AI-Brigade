@foreach($plats as $plat)
    <div class="plat-card">
        @if($plat->image)
            <img src="{{ Storage::disk('cloudinary')->url($plat->image) }}" 
                 alt="{{ $plat->nom }}" 
                 class="plat-image">
        @else
            <div class="plat-image-placeholder">No image</div>
        @endif
        
        <h3>{{ $plat->nom }}</h3>
        <p>{{ $plat->description }}</p>
        <p class="price">{{ $plat->prix }} €</p>
        <p class="stock">Stock: {{ $plat->stock }}</p>
        
        @if($plat->disponible)
            <span class="badge-available">Disponible</span>
        @else
            <span class="badge-unavailable">Indisponible</span>
        @endif
    </div>
@endforeach
