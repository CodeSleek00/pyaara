<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Fullscreen Anime Intro</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}

  body{
    min-height:100vh;
    font-family:sans-serif;
    background:#e8f5ff;
    overflow:hidden;
  }

  /* Website content (hidden initially) */
  #site{
    opacity:0;
    transform:translateY(20px);
    transition:.6s ease;
    text-align:center;
    padding:3rem 2rem;
  }
  #site.show{
    opacity:1;
    transform:translateY(0);
  }

  /* Intro overlay */
  .intro{
    position:fixed;
    inset:0;
    z-index:99;
    overflow:hidden;
    pointer-events:none;
  }

  /* Clouds container */
  .clouds{
    position:absolute;
    inset:0;
    z-index:5;
  }
  .cloud{
    position:absolute;
    width:140vw;
    height:60vh;
    left:-20vw;
    filter:blur(25px);
    opacity:0;
    background:radial-gradient(circle,rgba(20,20,25,.95),rgba(10,10,15,.85));
    border-radius:50%;
    transition:opacity .6s ease, transform 1.2s ease;
  }
  .c1{top:-20vh;}
  .c2{top:10vh;height:50vh;filter:blur(30px);}
  
  .clouds.show .cloud{opacity:1;}
  .clouds.exit .cloud{
    opacity:0;
    transform:translateY(-120vh);
    transition:1s ease;
  }

  /* FULLSCREEN ANIME IMAGE */
  .anime{
    position:absolute;
    inset:0;
    z-index:10;
    opacity:0;
    transform:scale(1.1);
    transition:opacity .7s ease, transform .8s ease;
  }
  .anime.show{
    opacity:1;
    transform:scale(1);
  }
  .anime.hide{
    opacity:0;
    transform:scale(0.95);
  }
  .anime img{
    width:100%;
    height:100%;
    object-fit:cover;   /* FULL SCREEN IMAGE */
  }
</style>
</head>
<body>

<!-- Intro Layer -->
<div class="intro" id="intro">
    <div class="clouds" id="clouds">
        <div class="cloud c1"></div>
        <div class="cloud c2"></div>
    </div>

    <div class="anime" id="anime">
        <!-- Replace with your anime image -->
        <img src="image.png" alt="anime">
    </div>
</div>

<!-- Main Website Content -->
<div id="site">
    <h1>Welcome to the Website</h1>
    <p>Your real content appears here after intro animation.</p>
</div>

<script>
const clouds = document.getElementById("clouds");
const anime = document.getElementById("anime");
const site = document.getElementById("site");
const intro = document.getElementById("intro");

function startIntro(){
  
  // Step 1: Clouds fade-in
  requestAnimationFrame(()=> clouds.classList.add("show"));

  // Step 2: Show fullscreen anime after cloud appear
  setTimeout(()=> anime.classList.add("show"), 800);

  // Step 3: Hide anime
  setTimeout(()=> {
    anime.classList.remove("show");
    anime.classList.add("hide");
  }, 2500);

  // Step 4: Clouds exit
  setTimeout(()=> clouds.classList.add("exit"), 3000);

  // Step 5: Reveal website
  setTimeout(()=> {
    site.classList.add("show");
    intro.style.display="none";
  }, 4200);
}

document.addEventListener("DOMContentLoaded", startIntro);
</script>

</body>
</html>
