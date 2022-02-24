<h1>パスワード変更依頼メールです</h1>
<br>
パスワード変更のご依頼を受け付けました。<br>
<br>
以下のURLをクリックしパスワードを変更してください。<br>
※身に覚えがない場合は、絶対にクリックしないでください！<br>
<br>
<a href="http://localhost:8000/reminder/<?php echo $token; ?>">{{ config('app.url') }}/reminder/<?php echo $token; ?></a><br>
<br>
クリック後、アプリからログインを行ってください。<br>
<br>
※このURLは登録から30分間有効です。<br>