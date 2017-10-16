<style>

</style>

<template>
  <div class="page">
    <form>
      <div class="grid-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <label>Name
              <input type="text" placeholder="Cafe name" v-model="name">
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Address
              <input type="text" placeholder="Address" v-model="address">
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>City
              <input type="text" placeholder="City" v-model="city">
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>State
              <input type="text" placeholder="State" v-model="state">
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <label>Zip
              <input type="text" placeholder="Zip" v-model="zip">
            </label>
          </div>
          <div class="large-12 medium-12 small-12 cell">
            <a class="button" v-on:click="submitNewCafe()">Add Cafe</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  export default {
    data(){
      return {
        name: '',
        address: '',
        city: '',
        state: '',
        zip: ''
      }
    },

    methods: {
      submitNewCafe(){
        this.$store.dispatch( 'addCafe', {
					name: this.name,
          address: this.address,
          city: this.city,
          state: this.state,
          zip: this.zip
				});
      }
    }
  }
</script>
