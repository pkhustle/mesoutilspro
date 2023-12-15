@section('site_title', formatTitle([e($transcription->name), __('Transcription'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => request()->is('admin/*') ? route('admin.dashboard') : route('dashboard'), 'title' => request()->is('admin/*') ? __('Admin') : __('Home')],
    ['url' => request()->is('admin/*') ? route('admin.transcriptions') : route('transcriptions'), 'title' => __('Transcriptions')],
    ['title' => __('Transcription')],
]])

<div class="d-flex align-items-end mb-3">
    <h1 class="h2 mb-0 flex-grow-1 text-truncate">{{ $transcription->name }}</h1>

    <div class="d-flex align-items-center flex-grow-0">
        <div class="form-row flex-nowrap">
            <div class="col">
                <form action="{{ route('transcriptions.edit', $transcription->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <textarea name="result" id="i-result-{{ $transcription->id }}" class="d-none">
                        {!! clean(encodeQuill($transcription->result)) !!}
                    </textarea>

                    <button type="submit" class="btn d-flex align-items-center" data-tooltip="true" title="{{ __('Save') }}">@include('icons.save', ['class' => 'fill-current width-4 height-4 text-secondary'])
                        &#8203;
                    </button>
                </form>
            </div>
            <div class="col">
                <button class="btn d-flex align-items-center" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}" data-clipboard="true" data-clipboard-target="#result-{{ $transcription->id }}">
                    @include('icons.content-copy', ['class' => 'fill-current width-4 height-4 text-secondary'])&#8203;
                </button>
            </div>
            <div class="col">
                <a href="#" class="btn text-secondary d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])
                    &#8203;</a>

                @include('transcriptions.partials.menu')
            </div>
        </div>
    </div>
</div>

<div class="card border-0 rounded-top shadow-sm overflow-hidden">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col d-flex align-items-center">
                <div class="d-flex align-items-center font-weight-medium py-1">
                    {{ __('Transcription') }}

                    @if($transcription->favorite) <div class="d-flex flex-shrink-0 width-4 height-4 text-warning {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-tooltip="true" title="{{ __('Favorite') }}">@include('icons.star', ['class' => 'fill-current width-4 height-4 flex-shrink-0'])</div> @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-header d-flex">
        <div class="py-1">
            @include('shared.editor.toolbar', ['id' => $transcription->id])
        </div>
    </div>
    <div class="card-body p-3">
        <div class="form-group m-0 p-1">
            <div class="form-control height-auto text-body {{ $errors->has('result') ? ' is-invalid' : ' p-0 border-0' }}">
                @include('shared.editor.content', ['id' => $transcription->id, 'text' => $transcription->result])
            </div>
            @if ($errors->has('result'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('result') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="card-footer p-0">
        <div class="row">
            <div class="col-12 col-lg text-truncate d-flex align-items-center justify-content-lg-center border-bottom border-bottom-lg-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                <div class="card-body text-truncate my-n2 d-flex align-items-center justify-content-lg-center">
                    <span class="height-6 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" data-tooltip="true" title="{{ __('Words') }}">
                        @include('icons.text-tags', ['class' => 'fill-current text-muted width-4 height-4'])
                    </span>

                    <span class="text-truncate text-muted" data-tooltip="true" title="{{ __('The number of words generated by the AI.') }} {{ __('Some language systems will use the following symbol to word ratios: :ratios.', ['ratios' => implode(', ', array_map(function($ratio) { return __(':ratio :scripts', ['ratio' => $ratio['value'], 'scripts' => '(' . implode(', ', array_map(function ($script) { return __($script); }, $ratio['scripts'])) . ')']); }, config('completions.ratios')))]) }}">
                        {{ ($transcription->words > 1 ? __(':number words', ['number' => number_format($transcription->words, 0, __('.'), __(','))]) : __(':number word', ['number' => number_format($transcription->words, 0, __('.'), __(','))])) }}
                    </span>
                </div>
            </div>
            <div class="col-12 col-lg text-truncate d-flex align-items-center justify-content-lg-center">
                <div class="card-body text-truncate my-n2 d-flex align-items-center justify-content-lg-center">
                    <span class="height-6 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" data-tooltip="true" title="{{ __('Created at') }}">
                        @include('icons.event', ['class' => 'fill-current text-muted width-4 height-4'])
                    </span>

                    <span class="text-truncate text-muted" data-tooltip="true" title="{{ $transcription->created_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }} {{ $transcription->created_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format('H:i:s') }}">
                        {{ $transcription->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
