<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ちょっとおてつだい</title>
    <link rel="stylesheet" type="text/css" href="../public/css/base.css">
</head>

<body>
    <header>
        <h1><a href="index.html">ヘッダのメインのロゴ</a></h1>
        <nav>
            <ul>
                <li><a href="login.html">ログイン</a></li>
                <li><a href="#about">新規登録</a></li>
                <li><a href="#join">おてつだい投稿</a></li>
            </ul>
        </nav>
        <div id="search">
            <div id="category">
                <ul>
                    <li><input type="radio" name="category" value="all" checked>すべて</li>
                    <li><input type="radio" name="category" value="helpme">てつだって</li>
                    <li><input type="radio" name="category" value="helpyou">てつだうよ</li>
                </ul>
            </div>
            <input type="text" name="freeword" placeholder="フリーワードを入力して検索">
            <input type="text" name="reward_lower">～
            <input type="text" name="reward_upper">
        </div>
        <div id="sort">
            <select name="sort" id="sort">
                <option selected value="new">新着</option>
                <option selected value="reward">報酬</option>
                <option selected value="favorites">お気に入り数</option>
                <option selected value="page_views">閲覧数</option>
            </select>
        </div>
        <div id="display">
            <ul>
                <li><a href="">list</a></li>
                <li><a href="">blocks</a></li>
                <li><a href="">map</a></li>
            </ul>
        </div>
    </header>
    <main>
        メイン
    </main>

    <footer>
        フッダ
    </footer>
</body>

</html>