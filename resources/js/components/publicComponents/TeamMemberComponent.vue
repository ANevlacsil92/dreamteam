<template>
  <div class="container container-main">
    <div class="row mt-5">
      <div class="col-md-3 d-flex justify-content-center text-center">
        <img :src="'/storage/' + member.extended_user_property.photo_url" class="img-fluid mb-2">
      </div>
      <div class="col-md-9 p d-flex justify-content-left">
        <div class="container-fluid">
        <div class="row">
          <h1>{{ member.name }}</h1>
        </div>
        <div class="row">
          <p>{{ member.extended_user_property.about_me }}</p>
        </div>
      </div>
      </div>
    </div>    
    <div class="row mt-4 ">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Bisher zu sehen als</h1>
      </div>
    </div>
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(role) in member.roles.filter(e => !e.name.toLowerCase().includes('regie'))" v-bind:key="role.id">
        <role-card :role="role" :actor="member" :type="'actor'" :play="role.play"></role-card>
      </div>     
    </div>    
    <div class="row mt-4 ">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Mitgewirkt als</h1>
      </div>
    </div>
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(role) in member.roles.filter(e => e.name.toLowerCase().includes('regie'))" v-bind:key="role.id">
        <role-card :role="role" :actor="member" :type="'actor'" :play="role.play"></role-card>
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
      member: null,
    };
  },
  methods: {
  },
  mounted() {
    this.member = this.params.member;
  },
};
</script>

<style scoped>
h1, h3 {
color: #f39200!important;
}
</style>