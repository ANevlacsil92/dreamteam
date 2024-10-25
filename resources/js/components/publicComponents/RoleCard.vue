<template>
<a class="anchor-no-dec w-100" :href="type=='actor' ? '/produktionen/' + play.shortlink_url : '/das-team/' + actor.shortlink_url">
  <div class="role-card">
    <img :src="'/images/plays/' + play.shortlink_url + '/' + role.photo_url" class="role-card-image" v-if="role.photo_url"/>
    <img :src="'/storage/' + actor.extended_user_property.photo_url" class="role-card-image" v-else-if="actor.extended_user_property && actor.extended_user_property.photo_url" onerror="this.src='/storage/imgs/profilepictures/logo_klein.png'"/>
    <img src="/storage/imgs/profilepictures/logo_klein.png" class="role-card-image" v-else/>
    <h1 v-if="type=='play'" class="role-card-cast">{{ actor.name }} <br/> als {{ role.name }}</h1>
    <h1 v-if="type=='actor'" class="role-card-cast">{{ role.name }} in <br/> {{ play.name }}</h1>
  </div>
</a>
</template>

<script>
export default {
  props: ["role", "actor", "play", "type"],
  data() {
    return {
    };
  },
  methods: {
  },
  mounted() {
  },
};
</script>

<style scoped>

</style>