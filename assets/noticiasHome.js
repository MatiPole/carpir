//NOTICIAS

const jsonNoticias = "assets/noticias.json";
const noticias = document.getElementById("containerNoticiasHome");

fetch(jsonNoticias)
  .then((res) => res.json())
  .then((data) => {
    const ultimaNoticia = data[data.length - 1];
    generarUltimaNoticia(ultimaNoticia);
  });

const generarUltimaNoticia = (noticia, index) => {
  // Genera un sufijo único para el ID del carousel basado en el índice
  const carouselId = `carouselExampleFade-${index}`;

  noticias.innerHTML += `
      <div class="container-noticias">
        <article class="article-noticias">
          <h3>${noticia.titulo}<span>${noticia.fecha}</span></h3>
          <p>
            ${noticia.noticia}
          </p>
          <a href="noticias.html"
            ><button class="ver-mas-button">Más Noticias</button></a
          >
        </article>
        <div id="${carouselId}" class="carousel carousel-fix slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2500">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="${noticia.img[0]}" class="d-block w-100" alt="${noticia.alt[0]}">
            </div>
            <div class="carousel-item">
              <img src="${noticia.img[1]}" class="d-block w-100" alt="${noticia.alt[1]}">
            </div>
            <div class="carousel-item">
              <img src="${noticia.img[2]}" class="d-block w-100" alt="${noticia.alt[2]}">
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
   `;
};
