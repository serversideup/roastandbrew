<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.cafe-card{
    border-radius: 5px;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
    padding: 5px;
    margin-top: 20px;
    cursor: pointer;
    -webkit-transform: scaleX(1) scaleY(1);
    transform: scaleX(1) scaleY(1);
    transition: .2s;

    span.title{
      display: block;
      text-align: center;
      color: $dark-color;
      font-family: 'Josefin Sans', sans-serif;
    }

    span.address{
      display: block;
      text-align: center;
      margin-top: 5px;
      color: $grey;
      font-family: 'Lato', sans-serif;

      span.street{
        font-size: 14px;
        display: block;
      }

      span.city{
        font-size: 12px;
      }

      span.state{
        font-size: 12px;
      }

      span.zip{
        font-size: 12px;
        display: block;
      }
    }

    &:hover{
      -webkit-transform: scaleX(1.041) scaleY(1.041);
      transform: scaleX(1.041) scaleY(1.041);
      transition: .2s;
    }
  }
</style>

<template>
  <div class="large-3 medium-3 small-6 cell">
    <router-link :to="{ name: 'cafe', params: { id: cafe.id} }">
      <div class="cafe-card">
        <span class="title">{{ cafe.name }}</span>
        <span class="address">
          <span class="street">{{ cafe.address }}</span>
          <span class="city">{{ cafe.city }}</span> <span class="state">{{ cafe.state }}</span>
          <span class="zip">{{ cafe.zip }}</span>
        </span>
      </div>
    </router-link>
  </div>
</template>

<script>
  export default {
    props: ['cafe']
  }
</script>
