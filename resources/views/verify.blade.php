<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>認証</title>
</head>

<body>
  <div>
    @if ($result == "success")
    <div class="content">
      <h1>認証コードを確認しました</h1>
      <div>
        ご登録ありがとうございました。
      </div>
    </div>
    @elseif($result == "exist")
    <div class="content">
      <h1>認証コードエラー</h1>
      <div>
        認証コードが間違っているか、すでに登録済みです。<br>
      </div>
    </div>
    @else
    <div class="content">
      <h1>認証コードエラー</h1>
      <div>
        認証コードの有効期限が過ぎているか、見つかりませんでした。<br>
        再送を希望の場合は、下記URLをクリックしてください。<br>
        <br>
        <a href="http://localhost:8000/resend/<?php echo $token; ?>">{{ config('app.url') }}/resend/<?php echo $token; ?></a><br>
      </div>
    </div>
    @endif
  </div>
</body>

</html>