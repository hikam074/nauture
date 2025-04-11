<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NauTure: Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-size: 1rem;
            font-family: 'Inter', Arial, Helvetica, sans-serif;
            color: white;
        }
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;

            background-image: url('images/backgrounds/signinBG.png');
            background-repeat: no-repeat; /* Gambar tidak diulang */
            background-position: center center; /* Gambar selalu di tengah */
            background-size: auto 100%; /* Tinggi penuh, lebar menyesuaikan */
            background-size: cover; /* Fallback untuk mengisi viewport */
        }
        .container {
            display: grid;
            place-items: center;
            height: 90%;
        }
        #homelink {
            height: 3rem;
            width: auto;
            padding-bottom: 2rem;
        }
        .form-input, #btn-submit {
            background-color: rgba(217, 217, 217, 0.6);
            padding: 12px;
            margin: 12px 0;
            border-radius: 12px;
            width: 100%;
            box-sizing: border-box;
            border: 2px solid transparent; /* Border awal tetap, tapi transparan */
            transition: border 0.3s, box-shadow 0.3s; /* Animasi halus */
        }
        input:focus {
            outline: none;
            border: 2px solid #CEF17B;
            box-shadow: 0 0 8px rgba(99, 139, 53, 0.5); /* Menambahkan efek glow */
            transition: border 0.3s, box-shadow 0.3s; /* Animasi halus */
        }
        #btn-submit {
            background-color: #638B35;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        }
        #btn-submit:hover {
            background-color: #76A74A; /* Warna hijau lebih terang */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan */
            transform: translateY(-3px); /* Tombol sedikit naik */
        }
        #btn-submit:active {
            background-color: #567B2A; /* Warna hijau lebih gelap */
            transform: translateY(0); /* Tombol kembali ke posisi semula */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Bayangan lebih kecil */
        }
        #title {
            font-size: 2rem;
        }
        #signup-href {
            color: #0F3714;
        }
        #title, #ucapan {
            margin: 0;
            text-align: center;
        }
        #signup {
            color: #638B35;
        }
        #error-message {
            margin: 0;
            visibility: hidden;
            color: red;
        }

        .remember-me-container {
            padding-top: 1rem;
        }
        .remember-me-inline {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }
        .remember-me-inline input {
            display: none; /* Hide default checkbox */
        }
        .checkmark {
            width: 20px;
            height: 20px;
            padding: 1px;
            border: 1px solid white;
            border-radius: 4px;
            position: relative;
            flex-shrink: 0;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            top: 3px;
            left: 7px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0; /* Mulai dengan tidak terlihat */
            transition: opacity 0.2s ease-in-out;
        }

        .remember-me-inline input:checked ~ .checkmark:after {
            opacity: 1; /* Tampilkan saat dicentang */
        }


    </style>
</head>
<body>

    <div class='container'>
        <a href="{{ route('homepage') }}"><img id="homelink" src="images/logos/homeLogo.png" alt="[alt]NauTure-Home"></a>
        <h3 id="title">Log In</h3>
        <p id="ucapan">Selamat Datang Kembali! Silahkan Log-in ke akun anda</p>
        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="email">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-input" id="email" name="email" required>
            </div>
            <div class="password">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-input" id="password" name="password" required>
                <p id="error-message">err</p>
            </div>
            <div class="remember-me-container">
                <label class="remember-me-inline">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <span class="checkmark"></span>
                    <span class="label-text">Ingat Saya</span>
                </label>
            </div>
            <div class="submit">
                <button type="submit" id="btn-submit">Log in</button>
                <p id="signup">Pengguna Baru? <a href="{{ route('register') }}" id="signup-href">Sign-up</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.querySelector("form");
            const errorMessage = document.getElementById("error-message");

            loginForm.addEventListener("submit", async function (e) {
                e.preventDefault(); // Cegah pengiriman form default

                const formData = new FormData(loginForm);
                const submitButton = loginForm.querySelector("button[type='submit']");
                submitButton.disabled = true; // Nonaktifkan tombol sementara

                try {
                    const response = await fetch(loginForm.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        if (data.message) {
                            errorMessage.textContent = data.message; // Ubah teks menjadi "return an error"
                            errorMessage.style.visibility = "visible"; // Tampilkan pesan error
                        }
                    } else {
                        const data = await response.json();
                        if (data.redirect) {
                            window.location.href = data.redirect; // Redirect ke halaman utama
                        }
                    }
                } catch (error) {
                    console.error("Something went wrong:", error);
                } finally {
                    submitButton.disabled = false; // Aktifkan kembali tombol
                }
            });
        });
    </script>

</body>
</html>
