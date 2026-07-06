<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uitgeschreven</title>
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #f3f4f6; }
        .card { background: white; border-radius: 12px; padding: 40px; max-width: 420px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        h1 { font-size: 1.4rem; color: #111; margin-bottom: 8px; }
        p { color: #555; line-height: 1.6; }
        a { color: #16a34a; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Je bent uitgeschreven</h1>
        <p>Je ontvangt geen afspraaknotificaties meer per email.</p>
        <p>Je kunt dit op elk moment terugzetten in je <a href="{{ config('app.url') }}/kapper/account">accountinstellingen</a>.</p>
    </div>
</body>
</html>
