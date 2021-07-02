<div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                [TKS] - Teste RPA
            </a>
            <form class="d-flex">
                <button wire:click="start" {{ $loading ? 'disabled' : ''}} class="btn btn-success" type="button">
                    @if($loading)
                        <span class="spinner-border spinner-border-sm text-white" role="status"></span>
                        Executando...
                    @else
                        Executar
                    @endif
                </button>
            </form>
        </div>
    </nav>

    <div wire:poll="loadImages" class="container">
        @foreach($images as $image)
        <div class="row">
            <div class="col">
                <div class="card mb-3 mt-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <a href="{{ asset('screenshots/'.$image->getFilename()) }}" data-lightbox="roadtrip">
                                <img src="{{ asset('screenshots/'.$image->getFilename()) }}" class="img-fluid rounded-start" alt="...">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{$image->getFilename()}}
                                </h5>
                                <p class="card-text"><small class="text-muted">
                                    {{ date('H:i:s d/m/Y', $image->getCTime()) }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
