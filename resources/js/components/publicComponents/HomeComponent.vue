<template>
  <div class="container-fluid container-main">
    <div class="row mt-3">
      
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Willkommen auf unserer Theaterhomepage!</h1>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Das Dreamteam Wien bedankt sich für euer Kommen und freut sich schon auf das nächste Projekt</h1>
      </div>
    </div>
    <div class="row pt-5 mb-5 pb-5 d-flex justify-content-center">
      <div class="col-4 col-sm-12">
        <div id="carouselExampleControls" class="carousel" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img class="d-block w-100 carousel-image" src="/images/logos/Dreamteam_Logo_bunt_hoch.svg">
            </div>
            <div class="carousel-item" v-for="image in images" v-bind:key="image.id">
              <img class="d-block w-100 carousel-image" :src="'/images/carousel/' +  image" >
            </div>
            
          </div>
          <a class="carousel-control-prev carousel-control" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next carousel-control" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
    <div class="row mt-5 pt-5 mb-0 pb-5 d-flex justify-content-center sponsor-section">
      <div class="col">
        <div class="container">
          <div class="row">
            <h1>Wir bedanken uns bei unseren Sponsoren</h1>
          </div>
          <div class="row mt-4">
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="https://afp-zt.at/"><img class="w-100" src="/images/sponsors/aignerpartner.jpg"/></a>
            </div>
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="https://fertax.at/"><img class="w-100" src="/images/sponsors/fertax.jpg"/></a>
            </div>
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="https://www.gross-gross.eu/"><img class="w-100" src="/images/sponsors/gross.jpg"/></a>
            </div>
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="https://autoreparatur-mainx.at/"><img class="w-100" src="/images/sponsors/mainx.svg"/></a>
            </div>
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="http://www.vest-immobilien.at/"><img class="w-100" src="/images/sponsors/vest.jpg"/></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      images:[],
    };
  },
  methods: {
    loadImages() {
      axios
        .get("/api/image-carousel")
        .then((response) => {
          this.images = response.data.map((e) => {
            return e.image;
          })
        })
        .catch((error) => {
          console.log(error);
        });
    }
  },
  mounted() {
    this.loadImages();
  },
};
</script>

<style scoped>
h1,
h3 {
  color: #f39200 !important;
}
a:hover {
  text-decoration: none;
}

.btn{
  background-color: #f39200 !important;
  color: white !important;
  border: none !important;
}

.modal-dialog {
  max-width: 400px;
}

.modal-dialog-landscape {
  max-width: 600px!important;
}

.carousel-image {
  max-height: 800px;
  object-fit: contain;
}

@media screen and (max-width: 1950px) {
  .carousel-image {
    max-height: 600px;
  }
}

@media screen and (max-width: 768px) {
  .carousel-image {
    max-height: 400px;
  }
}

@media screen and (max-width: 576px) {
  .carousel-image {
    max-height: 250px;
  }
}



.carousel-control{
  opacity: 0 !important;
}

.carousel-control:hover{
  opacity: 0.5 !important;
}

</style>