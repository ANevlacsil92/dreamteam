<template>
  <div class="container-fluid container-main">
    <div class="row mt-3">
      
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Willkommen auf unserer Theaterhomepage!</h1>
      </div>
    </div>

   
    <div class="row">
      <announcement-component></announcement-component>
    </div>

    <div class="row mt-5 pt-5 mb-0 pb-5 d-flex justify-content-center sponsor-section">
      <div class="col">
        <div class="container">
          <div class="row">
            <h1>Wir bedanken uns bei unseren Sponsoren</h1>
          </div>
          <div class="row mt-4">
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="https://fertax.at/"><img class="w-100" src="/images/sponsors/fertax.jpg"/></a>
            </div>
            <div class="col-md d-flex justify-content-center align-items-center px-2">
              <a href="https://www.gross-gross.eu/"><img class="w-100" src="/images/sponsors/gross.jpg"/></a>
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
      activeDoor: null,
    };
  },
  methods: {
    loadImages() {
      axios
        .get("/api/image-carousel")
        .then((response) => {
          // this.images = response.data; but images are named by numbers 1.jpg, 2.jpg, 3.jpg...
          let images = response.data;
          let imageArray = [];

          for (let i = 0; i <= images.length-1; i++) {
            imageArray.push(i + ".jpg");
          }

          this.images = imageArray;


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
h1 {
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