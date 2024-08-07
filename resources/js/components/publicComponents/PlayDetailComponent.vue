<template>
  <div class="container container-main" v-if="play">
    <div class="row mt-5">
      <div class="col-md-3 d-flex justify-content-center text-center">
        <img :src="'/images/plays/' + play.shortlink_url + '/' + play.cover_photo_url" class="play-card-image"/>
      </div>
      <div class="col-md-9 p d-flex justify-content-left">
        <div class="container-fluid">
        <div class="row">
          <h1>{{ play.name }}</h1>
        </div>
        <div class="row">
          <p><b>von {{ play.author }}</b></p>
        </div>
        <div class="row">
          <p>{{ play.description }}</p>
        </div>
        <div class="row">
          <p><b>{{ play.production_year }}<span v-if="play.production_rep_year"> & {{ play.production_rep_year }}</span></b></p>
        </div>
      </div>
      </div>
    </div>    
    <div class="row mt-4 " v-if="play.photos">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Fotos</h1>
      </div>
    </div>
     <!-- IMAGE CAROUSEL-->
     <div class="row pt-5 mb-5 pb-5 d-flex justify-content-center" v-if="play.photos">
      <div class="col-4 col-sm-12">
        <div id="carouselExampleControls" class="carousel" data-ride="carousel">
          <div class="carousel-inner">
            
            <div class="carousel-item" :class="{ 'active': i==0}" v-for="(p,i) in play.photos" v-bind:key="p.id" >
              <img class="d-block w-100 carousel-image"  :src="'/images/carouselarchive/' + play.shortlink_url + '/' +  p.photo_url" >
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
    <div class="row mt-4 ">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Besetzung</h1>
      </div>
    </div>
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(role) in play.roles.filter(e => !e.name.toLowerCase().includes('regiss'))" v-bind:key="role.id">
        <role-card :role="role" :actor="role.actor" :type="'play'" :play="play"></role-card>
      </div>     
    </div>   
    <div class="row mt-4 ">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Begleitet von</h1>
      </div>
    </div>
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(role) in play.roles.filter(e => e.name.toLowerCase().includes('regiss'))" v-bind:key="role.id">
        <role-card :role="role" :actor="role.actor" :type="'play'" :play="play"></role-card>
      </div>     
    </div>    
  </div>
</template>

<script>
import RoleCard from './RoleCard.vue';
export default {
  components: { RoleCard },
  props: ["params"],
  data() {
    return {
      play: null,
    };
  },
  methods: {
  },
  mounted() {
    this.play = this.params.play;
  },
};
</script>

<style scoped>
h1, h3 {
color: #f39200!important;
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