<div class="row justify-content-center mt-4">
    <div class="col-4">
        <div class="form-floating">
            <select class="form-select" name="expiration_date" id="expiration_date"
                    aria-label="select example">
                <option selected>Никогда</option>
                <option value="10_min">10 минут</option>
                <option value="1_hour">1 час</option>
                <option value="3_hour">3 час</option>
                <option value="1_day">1 день</option>
                <option value="1_week">1 неделя</option>
                <option value="1_month">1 месяц</option>
            </select>
            <label for="expiration_date">Срок</label>
        </div>
    </div>
    <div class="col-4">
        <div class="form-floating">
            <select class="form-select" name="access_type" id="access_type"
                    aria-label="select example">
                <option value="public">Всем</option>
                <option value="unlisted">Только по ссылке</option>
                <option value="private">Только вам</option>
            </select>
            <label for="access_type">Доступен</label>
        </div>
    </div>
    <div class="col-4">
        <div class="form-floating">
            <select class="form-select" name="text_type" id="text_type"
                    aria-label="select example">
                <option value="text">Обычный текст</option>
                <option value="php">Язык php</option>
                <option value="html">Язык html</option>
            </select>
            <label for="text_type">Какая паста?</label>
        </div>
    </div>

</div>

