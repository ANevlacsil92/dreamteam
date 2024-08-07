<template>
  <div class="container-fluid container-main">
    <div class="row mt-5 mb-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Unser Derzeitiges Ensemble</h1>
      </div>
    </div>      
    <div class="row mb-5 d-flex justify-content-center" v-for="(user,i) in usersActive" v-bind:key="user">
      <!--<team-member-photo-left-component :user=user></team-member-photo-left-component>-->
        <team-member-photo-right-component v-if="i%2==0" :user=user></team-member-photo-right-component>
        <team-member-photo-left-component v-else :user=user></team-member-photo-left-component>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Bereits mitgewirkt haben</h1>
      </div>
    </div>      
    <div class="row mb-5 d-flex justify-content-center" v-for="(user,i) in usersOld" v-bind:key="user">
      <!--<team-member-photo-left-component :user=user></team-member-photo-left-component>-->
        <team-member-photo-right-component v-if="i%2==0" :user=user></team-member-photo-right-component>
        <team-member-photo-left-component v-else :user=user></team-member-photo-left-component>
    </div>     
  </div>
</template>

<script>
export default {
  props: [],
  data() {
    return {
      usersActive:[],
      usersOld:[],
    };
  },
  methods: {
    loadData: function() {
      let _this = this;

      $.ajax({
        url: "/api/the-team",
        success: function (data) {
          _this.usersActive = data.active;
          _this.usersOld = data.old;
        },
      });
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>

<style scoped>
h1, h3 {
color: #f39200!important;
}
</style>