<template>
  <div class="container-fluid container-main">
    <div class="row mt-5 mb-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Unser DreamTeam</h1>
      </div>
    </div>      
    <div class="row mb-5 d-flex justify-content-center" v-for="(user,i) in users" v-bind:key="user.id">
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
      users:[],
    };
  },
  methods: {
    loadData: function() {
      let _this = this;

      $.ajax({
        url: "/api/the-team",
        success: function (data) {
          _this.users = data;
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