<div class="card p-5 mb-5">

    <h5 class="mb-4">Файл спецификации</h5>

    <div class="row">
        <div class="col-lg-6">
            <div class="custom-file">
                <input type="file" name="specification" class="custom-file-input" id="specification-file">
                <label class="custom-file-label" for="specification-file">Выберите файл</label>
            </div>
        </div>
    </div>

</div>

<div class="card p-5 mb-5">

    <h5 class="mb-4">Видео <small>(Youtube ссылка <strong>ИЛИ</strong> файл в 2-х форматах)</small></h5>

    <div class="row">
        <div class="col-lg-6">

            <div class="mb-4">
                <input id="video_youtube" class="w-100" type="text" name="video_youtube" value="{{ old('video_youtube') }}"
                       placeholder="Youtube ссылка">
            </div>

            <div class="custom-file mb-2">
                <input type="file" name="video_mp4" class="custom-file-input" id="video_mp4" accept="video/mp4">
                <label class="custom-file-label" for="video_mp4">Выберите файл mp4</label>
            </div>

            <div class="custom-file">
                <input type="file" name="video_webm" class="custom-file-input" id="video_webm" accept="video/webm">
                <label class="custom-file-label" for="video_webm">Выберите файл webm</label>
            </div>

        </div>
    </div>

</div>
