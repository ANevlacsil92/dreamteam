<template>
  <div class="container">
    <div class="row w-100 pl-2">
      <h3>{{ user.name }}</h3>
    </div>
    <div class="row">
      <div class="col-md-3">
        <img :src="'/storage/' + user.photo_url" class="img-fluid mb-2">
      </div>
      <div class="col-md-9">
        <p class="team-member-description">{{ user.about_me }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {  
    };
  },
};
</script>

<style scoped>
h1, h3 {
color: #f39200!important;
}

.team-member-description{
  font-size: 1.1rem;
  line-height: 1.5rem;
}
</style>