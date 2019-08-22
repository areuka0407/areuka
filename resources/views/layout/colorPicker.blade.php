@push("head")
    <link rel="stylesheet" href="/assets/css/layout/colorPicker.css">
    <script type="text/javascript" src="/assets/js/layout/colorPicker.js"></script>
@endpush

@push("anything")
    <div id="colorPicker">
        <div class="close">&times;</div>
        <div class="main-select-box f-left mr-2">
            <div class="select">
                <span></span>
            </div>
            <canvas width="300" height="300" data-id="main-canvas"></canvas>
        </div>
        <div class="sub-select-box f-left">
            <canvas width="30" height="300" data-id="sub-canvas"></canvas>
            <div class="select"></div>
        </div>
        <div class="color-form f-left">
            <div class="form-group mb-5">
                <div class="pre-view" data-red="0" data-green="0" data-blue="0">
                </div>
            </div>
            <div class="form-group">
                <label for="color-red">R</label>
                <input type="text" class="color-input" id="color-red" maxlength="3" value="255" onchange="this.value = this.value.trim() === '' || !this.value ? 0 : this.value.trim();" pattern="[0-9]{1,3}">
            </div>
            <div class="form-group">
                <label for="color-green">G</label>
                <input type="text" class="color-input" id="color-green" maxlength="3" value="255" onchange="this.value = this.value.trim() === '' || !this.value ? 0 : this.value.trim();">
            </div>
            <div class="form-group">
                <label for="color-blue">B</label>
                <input type="text" class="color-input" id="color-blue" maxlength="3" value="255" onchange="this.value = this.value.trim() === '' || !this.value ? 0 : this.value.trim();">
            </div>
            <div class="form-group">
                <span>#</span>
                <input type="text" class="color-input" id="color-hex" maxlength="6" value="FFFFFF" onchange="this.value = this.value.trim() === '' ? '000000' : this.value.trim();">
            </div>
            <div class="form-group">
                <button class="btn mt-2 submit-btn">확인</button>
            </div>
        </div>
    </div>
@endpush
