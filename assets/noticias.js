//NOTICIAS

const jsonNoticias = "assets/noticias.json";
const noticias = document.getElementById("noticiasall");

fetch(jsonNoticias)
  .then((res) => res.json())
  .then((data) => {
    data.reverse();
    generarNoticias(data);
  });

const generarNoticias = (data) => {
  data.forEach((noticia, index) => {
    // Genera un sufijo único para el ID del carousel basado en el índice
    const carouselId = `carouselExampleFade-${index}`;
    const carouselModalId = `carouselExample-${index}`;
    const ModalId = `modalId-${index}`;
    noticias.innerHTML += `
      <div class="container-noticias">
        <article class="article-noticias">
          <h3>${noticia.titulo}<span>${noticia.fecha}</span></h3>
          <p>
            ${noticia.noticia}
          </p>
             <!-- Button trigger modal -->
             <div>
  <button
    type="button"
    class="btn ver-mas-button"
    data-bs-toggle="modal"
    data-bs-target="#${ModalId}"
  >
    Ver más fotos
  </button>
  ${
    noticia.videoClip === true
      ? `<a class="btn ver-video-button" href="${noticia.linkVideoClip}" target="_blank">Ver video</a>`
      : ``
  }</div>
        </article>
        <div id="${carouselId}" class="carousel carousel-fix slide carousel-fade" data-bs-ride="carousel" data-bs-interval="2500">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="${noticia.img[0]}" class="d-block w-100" alt="${
      noticia.img[0]
    }">
            </div>
            <div class="carousel-item">
              <img src="${noticia.img[1]}" class="d-block w-100" alt="${
      noticia.img[1]
    }">
            </div>
            <div class="carousel-item">
              <img src="${noticia.img[2]}" class="d-block w-100" alt="${
      noticia.img[2]
    }">
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


  <!-- Modal -->
  <div
    class="modal fade"
    id="${ModalId}"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
  <div class="container-close">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
</div>
        <div class="modal-body">
        <div id="${carouselModalId}" class="carousel slide">
  <div class="carousel-inner">
    <div class="carousel-item active">
  ${
    noticia.imgExtras[0].endsWith(".mp4")
      ? `<video src="${noticia.imgExtras[0]}" controls width="500" height="325"></video>`
      : `<img src="${noticia.imgExtras[0]}" class="d-block w-100" alt="${noticia.imgExtras[0]}">`
  }
    </div>
    <div class="carousel-item"> 
  ${
    noticia.imgExtras[1].endsWith(".mp4")
      ? `<video src="${noticia.imgExtras[1]}" controls width="500" height="325"></video>`
      : `<img src="${noticia.imgExtras[1]}" class="d-block w-100" alt="${noticia.imgExtras[1]}">`
  }
    </div>

    <div class="carousel-item">
  ${
    noticia.imgExtras[2].endsWith(".mp4")
      ? `<video src="${noticia.imgExtras[2]}" controls width="500" height="325"></video>`
      : `<img src="${noticia.imgExtras[2]}" class="d-block w-100" alt="${noticia.imgExtras[2]}">`
  }
    </div>
  </div>
  <button class="carousel-control-prev carousel-control-prev-modal" type="button" data-bs-target="#${carouselModalId}" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next carousel-control-next-modal" type="button" data-bs-target="#${carouselModalId}" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div></div>
        
        
         
        </div>
      </div>
    </div>
  </div>
      </div>
   `;
  });
};
