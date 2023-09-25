<template>
  <div class="container container-main">
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
    <div class="row mt-4 ">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Besetzung</h1>
      </div>
    </div>
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(role) in play.roles.filter(e => !e.name.toLowerCase().includes('regie'))" v-bind:key="role.id">
        <role-card :role="role" :actor="role.actor" :type="'play'" :play="play"></role-card>
      </div>     
    </div>   
    <div class="row mt-4 ">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Begleitet von</h1>
      </div>
    </div>
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(role) in play.roles.filter(e => e.name.toLowerCase().includes('regie'))" v-bind:key="role.id">
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
</style>