<div class="card p-5 mb-5">

    <div class="row">
        <div class="col-lg-6">

            <div class="alert-info p-2 mb-5">Укажите Youtube ссылку на поток <strong>ИЛИ</strong> загрузите файл (в 1-м или 2-х
                форматах)
            </div>

            <div class="mb-5">
                <input id="video_youtube" class="w-100" type="text" name="video_youtube"
                       value="{{ old('video_youtube') }}"
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
