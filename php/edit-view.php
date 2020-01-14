<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="icon" href="favicon.png">
    <title>Služebníček 2.D | Editace</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
  </head>
  <body>
    <div id="header">
      <a href="index.php"><p>Služebníček 2.D</p></a>
      <span class="vl"></span>
      <a href="index.php"><p>Přehled</p></a>
      <a href="#"><p>Přidat úkol</p></a>
    </div>
  </body>

  <div class="input">
    <form method="POST">
      <span>Předmět: </span>
      <select name="predmet" required>
        <option value="">- vyber předmět -</option>
        <?php foreach ($predmety as $predmet) { ?>
          <option value="<?= $predmet ?>" <?= @$newTask['predmet']==$predmet ? 'selected' : ''?> ><?= $predmet ?></option>
        <?php } ?>
      </select>
        <br>

        <div style="display:flex;">
          <div style="margin-right: 0.5em;">
            Typ záznamu:
          </div>
          <div>
            <label>
              <input type="radio" name="typ_zaznamu" value="U" <?= @$newTask['typ_zaznamu']!='P' ? 'checked' : '' ?>>
              Domácí úkol
            </label>
            <br>
            <label>
              <input type="radio" name="typ_zaznamu" value="P" <?= @$newTask['typ_zaznamu']=='P' ? 'checked' : '' ?>>
              Písemná práce
            </label>
          </div>
        </div>

        Skupina:
        <label>
          <input type="checkbox" name="skupina1" name="skupina_1" <?= (@$newTask['skupina'] == '1 + 2' || @$newTask['skupina'] == '1') ? 'checked' : '' ?>>
          1
        </label>
        <label>
          <input type="checkbox" name="skupina2" name="skupina_2" <?= (@$newTask['skupina'] == '1 + 2' || @$newTask['skupina'] == '2') ? 'checked' : '' ?>>
          2
        </label>
        <br>

        <span>Popis:</span>
        <input type="text" name="popis" placeholder="př: UNPH 97/8 a,b,c" value="<?= @$newTask['popis'] ?>">
        <br>
        <span>Datum zadání: </span>
        <input id="today" type="date" name="datum_zadani" value="<?= @$newTask['datum_zadani'] ?>">
        <br>
        <span>Datum odevzdání: </span>
        <input id="todayplus" type="date" name="datum_odevzdani" value="<?= @$newTask['datum_odevzdani'] ?>">
        <br>
        <input type="password" name="passwd" placeholder="Přidávací heslo" value="<?= @ $newTask['passwd'] ?>" required>
        <br>
        <input id="submit" type="submit" value="Změnit">
      </form>
    <?php if(@$errorMessage): ?>
    <div class="error message">
      <?= $errorMessage ?>
    </div>
    <?php endif ?>
  </div>


</html>

<script>
    let today = new Date().toISOString().substr(0, 10);
    let todayplus = new Date();
    todayplus.setDate(todayplus.getDate() + 1);
    if (document.querySelector("#today").value == '') {
      document.querySelector("#today").value = today;
      document.querySelector("#todayplus").value = todayplus.toISOString().substr(0, 10);
    }
</script>
