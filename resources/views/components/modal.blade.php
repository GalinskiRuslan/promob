<div class="modal fade" id="{{ $id ?? 'modal' }}" tabindex="-1" aria-labelledby="{{ $id ?? 'modal' }}Label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id ?? 'modal' }}Label">{{ $title ?? 'Modal Title' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
            FACK!
        </div>
    </div>
</div>
