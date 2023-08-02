<!DOCTYPE html>
<html>

<head>
    <title>Contoh Minify Blade</title>
    <style >
        /* Contoh CSS */
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <h1>Hello, World!</h1>
    <p>Ini adalah contoh secure code.</p>
    <button id="klikButton">Muat Data</button>
    <button id="klikButtonduwa">Muat Data 2</button>

    <!-- Contoh komentar -->
    <script>
        var klikButton = document.getElementById("klikButton");

        // Tambahkan event listener untuk menghandle klik tombol
        klikButton.addEventListener("click", function() {
            alert("Anda telah mengklik tombol bosku!")
            var saya = 'dia'
            alert(saya)
        });
    </script>
    <script ignore-code-obfuscate>
        var klikButton = document.getElementById("klikButtonduwa");

        // Tambahkan event listener untuk menghandle klik tombol
        klikButton.addEventListener("click", function() {
            alert("Anda telah mengklik tombol bosku 2!");
        });
    </script>
</body>

</html>
