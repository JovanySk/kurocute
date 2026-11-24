@extends('master')

@section('title', 'Inicio')

@section('contenido')
    <div class="header-container text-center">
        <div class="container">
            <h1 class="display-4 fw-bold titulo-principal">
                Bienvenido a KuroCute
            </h1>
            <p class="lead subtitulo">
                Descubre las mejores figuras de Kuromi
            </p>
        </div>
    </div>

    <div class="carousel-container">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/ima4.jpg') }}" class="d-block w-100 carousel-img" alt="Producto 1" style="height: 700px; object-fit: cover;"> {{-- Estilo en línea --}}
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/ima2.jpg') }}" class="d-block w-100 carousel-img" alt="Producto 2" style="height: 700px; object-fit: cover;"> {{-- Estilo en línea --}}
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/ima3.jpg') }}" class="d-block w-100 carousel-img" alt="Producto 3" style="height: 700px; object-fit: cover;"> {{-- Estilo en línea --}}
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/ima6.jpg') }}" class="d-block w-100 carousel-img" alt="Producto 4" style="height: 700px; object-fit: cover;"> {{-- Estilo en línea --}}
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/ima5.jpg') }}" class="d-block w-100 carousel-img" alt="Producto 5" style="height: 700px; object-fit: cover;"> {{-- Estilo en línea --}}
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">Nuestros Productos</h2>
        <div class="row">
            @foreach ($productos as $producto)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if ($producto->imagen)
                            @php
                                $rutaImagen = 'productos/' . basename($producto->imagen);
                            @endphp
                            
                            @if(file_exists(public_path($rutaImagen)))
                                <img src="{{ asset($rutaImagen) }}" class="card-img-top"
                                    alt="{{ $producto->nombre }}"
                                    onerror="this.src='https://via.placeholder.com/300x200?text=Error+al+cargar'">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=Imagen+no+encontrada" 
                                    class="card-img-top"
                                    alt="Imagen no encontrada">
                            @endif
                        @else
                            <img src="https://via.placeholder.com/300x200?text=Sin+Imagen" class="card-img-top"
                                alt="Sin imagen">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text"><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
                            <p class="card-text"><strong>Stock:</strong> {{ $producto->cantidad }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection