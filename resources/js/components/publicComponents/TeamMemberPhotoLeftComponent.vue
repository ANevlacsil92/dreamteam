<template>
  <div class="container">
    <div class="row w-100 pl-2">
      <a :href="'/das-team/' + user.shortlink_url" class="anchor-no-dec" >
        <h3>{{ user.name }}</h3>
      </a>
    </div>
    <div class="row">
      <div class="col-md-3">
        <a :href="'/das-team/' + user.shortlink_url" class="anchor-no-dec" >
          <img :src="'/storage/' + user.photo_url" class="img-fluid mb-2">
        </a>
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