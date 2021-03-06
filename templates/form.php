<!--модальное окно добавления задачи-->

<div class="modal">
  <button class="modal__close" type="button" name="button">Закрыть</button>

  <h2 class="modal__heading">Добавление задачи</h2>

  <form class="form" action="index.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>

      <input class="form__input <?php if (in_array('name', $errorsss)) print($errorClass) ?>" type="text" name="name" id="name" value="<?= $task ?>" placeholder="Введите название">

      <?php if (in_array('name', $errorsss)) print($errorEmpty) ?>
    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект <sup>*</sup></label>

      <select class="form__input form__input--select <?php if (in_array('project', $errorsss)) print($errorClass) ?>" name="project" id="project">

      <?php foreach ($projectArr as $key => $value) : ?>
      <?php  if ($key !== 0) : ?>

        <option value="<?= $key ?>" <?php if ($project == $key) print('selected') ?>>
            <?= $value ?>
        </option>

      <?php endif; ?>
      <?php endforeach; ?>

      </select>

      <?php if (in_array('project', $errorsss)) print($errorEmpty) ?>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

      <input class="form__input form__input--date <?php if (in_array('date', $errorsss) || in_array('dateFormat', $errorsss)) print($errorClass) ?>" type="text" name="date" id="date" value="<?= $date ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

      <?php if (in_array('date', $errorsss)) print($errorEmpty) ?>
      <?php if (in_array('dateFormat', $errorsss)) print($errorFormat) ?>

    </div>

    <div class="form__row">
      <label class="form__label">Файл</label>

      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="preview" id="preview" value="">

        <label class="button button--transparent" for="preview">
            <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="taskSubmit" value="Добавить">
    </div>
  </form>
</div>
