<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-brew-method{
    label{
      font-weight: bold;
      color: black;
      font-size: 16px;
      margin-top: 15px;
    }

    a.update-brew-method{
      display: block;
      width: 150px;
      color: white;
      background-color: #CCC;
      text-align: center;
      border-radius: 5px;
      margin-top: 20px;
      height: 45px;
      line-height: 45px;
      margin-bottom: 100px;
    }

    img.brew-method-icon{
      width: 50px;
    }

    a.change-icon{
      display: block;
      margin-top: 10px;
      color: $secondary-color;
    }

    div.change-icon-modal{
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba( 0, 0, 0, .6 );
      z-index: 99999;

      div.modal-box{
        width: 100%;
        max-width: 530px;
        min-width: 320px;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        -webkit-box-shadow: 0 1px 3px rgba(50,50,50,0.08);
        box-shadow: 0 1px 3px rgba(50,50,50,0.08);
        -webkit-border-radius: 4px;
        border-radius: 4px;
        font-size: 16px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 500px;
        overflow-y: auto;

        label{
          font-weight: bold;
        }

        div.icon-selection-container{
          margin-top: 10px;
        }

        div.new-icon-container{
          text-align: center;
          cursor: pointer;
          margin-bottom: 20px;
          border-radius: 5px;
          padding: 5px;

          &.active{
            background-color: $secondary-color;
            color: white;
          }

          img.new-icon{
            display: block;
            margin: auto;
            margin-bottom: 10px;
            height: 30px;
          }
        }
      }
    }
  }
</style>

<template>
  <div id="admin-brew-method">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <h3 class="page-header"><router-link :to="{ name: 'admin-brew-methods'}">Brew Methods</router-link> > {{ brewMethod.method }}</h3>
        </div>
      </div>
    </div>
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Method</label>
          <input type="text" v-model="method"/>
          <span class="validation" v-show="!validations.method">Please enter a name for the brew method</span>
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <label>Icon</label>
          <img class="brew-method-icon" v-bind:src="icon != '' ? icon+'.svg' : ''"/>
          <a v-on:click="promptChangeIcon()" class="change-icon">Change Icon</a>
        </div>
      </div>

      <div class="grid-x">
        <div class="large-8 medium-12 cell">
          <a class="update-brew-method" v-on:click="updateBrewMethod()">Update</a>
        </div>
      </div>
    </div>

    <div class="change-icon-modal" v-show="showChangeIcon" v-on:click="hideChangeIcon()">
      <div class="modal-box" v-on:click.stop="">
        <div class="grid-x icon-selection-container">
          <div class="large-12 medium-12 cell">
            <label>Icon</label>
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/aeropress'}" v-on:click="selectIcon('/img/icons/aeropress')">
            <img class="new-icon" src="/img/icons/aeropress.svg"/>
            Aeropress
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/biscuit'}" v-on:click="selectIcon('/img/icons/biscuit')">
            <img class="new-icon" src="/img/icons/biscuit.svg"/>
            Biscuit
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/bottle-of-water'}" v-on:click="selectIcon('/img/icons/bottle-of-water')">
            <img class="new-icon" src="/img/icons/bottle-of-water.svg"/>
            Bottle of Water
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/chemex'}" v-on:click="selectIcon('/img/icons/chemex')">
            <img class="new-icon" src="/img/icons/chemex.svg"/>
            Chemex
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/chocolate'}" v-on:click="selectIcon('/img/icons/chocolate')">
            <img class="new-icon" src="/img/icons/chocolate.svg"/>
            Chocolate
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/cinnamon'}" v-on:click="selectIcon('/img/icons/cinnamon')">
            <img class="new-icon" src="/img/icons/cinnamon.svg"/>
            Cinnamon
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/clover'}" v-on:click="selectIcon('/img/icons/clover')">
            <img class="new-icon" src="/img/icons/clover.svg"/>
            Clover
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-app'}" v-on:click="selectIcon('/img/icons/coffee-app')">
            <img class="new-icon" src="/img/icons/coffee-app.svg"/>
            Coffee App
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-beans'}" v-on:click="selectIcon('/img/icons/coffee-beans')">
            <img class="new-icon" src="/img/icons/coffee-beans.svg"/>
            Coffee Beans
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-capsules'}" v-on:click="selectIcon('/img/icons/coffee-capsules')">
            <img class="new-icon" src="/img/icons/coffee-capsules.svg"/>
            Coffee Capsules
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-coctail'}" v-on:click="selectIcon('/img/icons/coffee-coctail')">
            <img class="new-icon" src="/img/icons/coffee-cocktail.svg"/>
            Coffee Cocktail
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-cup'}" v-on:click="selectIcon('/img/icons/coffee-cup')">
            <img class="new-icon" src="/img/icons/coffee-cup.svg"/>
            Coffee Cup
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-is-love'}" v-on:click="selectIcon('/img/icons/coffee-is-love')">
            <img class="new-icon" src="/img/icons/coffee-is-love.svg"/>
            Coffee Is Love
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-mill'}" v-on:click="selectIcon('/img/icons/coffee-mill')">
            <img class="new-icon" src="/img/icons/coffee-mill.svg"/>
            Coffee Mill
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-pack'}" v-on:click="selectIcon('/img/icons/coffee-pack')">
            <img class="new-icon" src="/img/icons/coffee-pack.svg"/>
            Coffee Pack
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-pods'}" v-on:click="selectIcon('/img/icons/coffee-pods')">
            <img class="new-icon" src="/img/icons/coffee-pods.svg"/>
            Coffee Pods
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-pot'}" v-on:click="selectIcon('/img/icons/coffee-pot')">
            <img class="new-icon" src="/img/icons/coffee-pot.svg"/>
            Coffee Pot
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-scoop'}" v-on:click="selectIcon('/img/icons/coffee-scoop')">
            <img class="new-icon" src="/img/icons/coffee-scoop.svg"/>
            Coffee Scoop
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-shake'}" v-on:click="selectIcon('/img/icons/coffee-shake')">
            <img class="new-icon" src="/img/icons/coffee-shake.svg"/>
            Coffee Shake
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-shop-sign'}" v-on:click="selectIcon('/img/icons/coffee-shop-sign')">
            <img class="new-icon" src="/img/icons/coffee-shop-sign.svg"/>
            Coffee Shop Sign
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-shop'}" v-on:click="selectIcon('/img/icons/coffee-shop')">
            <img class="new-icon" src="/img/icons/coffee-shop.svg"/>
            Coffee Shop
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-to-go'}" v-on:click="selectIcon('/img/icons/coffee-to-go')">
            <img class="new-icon" src="/img/icons/coffee-to-go.svg"/>
            Coffee To Go
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-tree'}" v-on:click="selectIcon('/img/icons/coffee-tree')">
            <img class="new-icon" src="/img/icons/coffee-tree.svg"/>
            Coffee Tree
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-with-cream'}" v-on:click="selectIcon('/img/icons/coffee-with-cream')">
            <img class="new-icon" src="/img/icons/coffee-with-cream.svg"/>
            Coffee With Cream
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffee-with-ice-cream'}" v-on:click="selectIcon('/img/icons/coffee-with-ice-cream')">
            <img class="new-icon" src="/img/icons/coffee-with-ice-cream.svg"/>
            Coffee With Ice Cream
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/coffeemaker'}" v-on:click="selectIcon('/img/icons/coffeemaker')">
            <img class="new-icon" src="/img/icons/coffeemaker.svg"/>
            Coffee Maker
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/cold-brew'}" v-on:click="selectIcon('/img/icons/cold-brew')">
            <img class="new-icon" src="/img/icons/cold-brew.svg"/>
            Cold Brew
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/croissant'}" v-on:click="selectIcon('/img/icons/croissant')">
            <img class="new-icon" src="/img/icons/croissant.svg"/>
            Croissant
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/cupcake'}" v-on:click="selectIcon('/img/icons/cupcake')">
            <img class="new-icon" src="/img/icons/cupcake.svg"/>
            Cupcake
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/donut'}" v-on:click="selectIcon('/img/icons/donut')">
            <img class="new-icon" src="/img/icons/donut.svg"/>
            Donut
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/drip-brew'}" v-on:click="selectIcon('/img/icons/drip-brew')">
            <img class="new-icon" src="/img/icons/drip-brew.svg"/>
            Drip Brew
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/drip-kettle'}" v-on:click="selectIcon('/img/icons/drip-kettle')">
            <img class="new-icon" src="/img/icons/drip-kettle.svg"/>
            Drip Kettle
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/electric-kettle'}" v-on:click="selectIcon('/img/icons/electric-kettle')">
            <img class="new-icon" src="/img/icons/electric-kettle.svg"/>
            Electric Kettle
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/espresso-cup'}" v-on:click="selectIcon('/img/icons/espresso-cup')">
            <img class="new-icon" src="/img/icons/espresso-cup.svg"/>
            Espresso Cup
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/espresso-tamper'}" v-on:click="selectIcon('/img/icons/espresso-tamper')">
            <img class="new-icon" src="/img/icons/espresso-tamper.svg"/>
            Espresso Tamper
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/espresso'}" v-on:click="selectIcon('/img/icons/espresso')">
            <img class="new-icon" src="/img/icons/espresso.svg"/>
            Espresso
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/eva-solo'}" v-on:click="selectIcon('/img/icons/eva-solo')">
            <img class="new-icon" src="/img/icons/eva-solo.svg"/>
            Eva Solo
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/fizzy-water'}" v-on:click="selectIcon('/img/icons/fizzy-water')">
            <img class="new-icon" src="/img/icons/fizzy-water.svg"/>
            Fizzy Water
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/frappe'}" v-on:click="selectIcon('/img/icons/frappe')">
            <img class="new-icon" src="/img/icons/frappe.svg"/>
            Frappe
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/french-press'}" v-on:click="selectIcon('/img/icons/french-press')">
            <img class="new-icon" src="/img/icons/french-press.svg"/>
            French Press
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/frozen-frappe'}" v-on:click="selectIcon('/img/icons/frozen-frappe')">
            <img class="new-icon" src="/img/icons/frozen-frappe.svg"/>
            Frozen Frappe
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/green-tea'}" v-on:click="selectIcon('/img/icons/green-tea')">
            <img class="new-icon" src="/img/icons/green-tea.svg"/>
            Green Tea
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/hario'}" v-on:click="selectIcon('/img/icons/hario')">
            <img class="new-icon" src="/img/icons/hario.svg"/>
            Hario
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/hot-chocolate'}" v-on:click="selectIcon('/img/icons/hot-chocolate')">
            <img class="new-icon" src="/img/icons/hot-chocolate.svg"/>
            Hot Chocolate
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/i-love-coffee'}" v-on:click="selectIcon('/img/icons/i-love-coffee')">
            <img class="new-icon" src="/img/icons/i-love-coffee.svg"/>
            I Love Coffee
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/iced-coffee_2'}" v-on:click="selectIcon('/img/icons/iced-coffee_2')">
            <img class="new-icon" src="/img/icons/iced-coffee_2.svg"/>
            Iced Coffee
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/iced-tea'}" v-on:click="selectIcon('/img/icons/iced-tea')">
            <img class="new-icon" src="/img/icons/iced-tea.svg"/>
            Iced Tea
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/instant-coffee'}" v-on:click="selectIcon('/img/icons/instant-coffee')">
            <img class="new-icon" src="/img/icons/instant-coffee.svg"/>
            Instant Coffee
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/kalita-wave'}" v-on:click="selectIcon('/img/icons/kalita-wave')">
            <img class="new-icon" src="/img/icons/kalita-wave.svg"/>
            Kalita Wave
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/kettle'}" v-on:click="selectIcon('/img/icons/kettle')">
            <img class="new-icon" src="/img/icons/kettle.svg"/>
            Kettle
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/latte_2'}" v-on:click="selectIcon('/img/icons/latte_2')">
            <img class="new-icon" src="/img/icons/latte_2.svg"/>
            Latte
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/latte'}" v-on:click="selectIcon('/img/icons/latte')">
            <img class="new-icon" src="/img/icons/latte.svg"/>
            Latte
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/matcha-latte'}" v-on:click="selectIcon('/img/icons/matcha-latte')">
            <img class="new-icon" src="/img/icons/matcha-latte.svg"/>
            Matcha Latte
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/milk-pitcher'}" v-on:click="selectIcon('/img/icons/milk-pitcher')">
            <img class="new-icon" src="/img/icons/milk-pitcher.svg"/>
            Milk Pitcher
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/milk-product'}" v-on:click="selectIcon('/img/icons/milk-product')">
            <img class="new-icon" src="/img/icons/milk-product.svg"/>
            Milk Product
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/moka-pot'}" v-on:click="selectIcon('/img/icons/moka-pot')">
            <img class="new-icon" src="/img/icons/moka-pot.svg"/>
            Moka Pot
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/nitrous'}" v-on:click="selectIcon('/img/icons/nitrous')">
            <img class="new-icon" src="/img/icons/nitrous.svg"/>
            Nitrous
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/percolator'}" v-on:click="selectIcon('/img/icons/percolator')">
            <img class="new-icon" src="/img/icons/percolator.svg"/>
            Percolator
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/porcelain-teapot'}" v-on:click="selectIcon('/img/icons/porcelain-teapot')">
            <img class="new-icon" src="/img/icons/porcelain-teapot.svg"/>
            Porcelain Teapot
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/portafilter'}" v-on:click="selectIcon('/img/icons/portafilter')">
            <img class="new-icon" src="/img/icons/portafilter.svg"/>
            Portafilter
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/sack-of-coffee-beans'}" v-on:click="selectIcon('/img/icons/sack-of-coffee-beans')">
            <img class="new-icon" src="/img/icons/sack-of-coffee-beans.svg"/>
            Sack of Coffee Beans
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/sugar-and-milk'}" v-on:click="selectIcon('/img/icons/sugar-and-milk')">
            <img class="new-icon" src="/img/icons/sugar-and-milk.svg"/>
            Sugar and Milk
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/sugar'}" v-on:click="selectIcon('/img/icons/sugar')">
            <img class="new-icon" src="/img/icons/sugar.svg"/>
            Sugar
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/syphon'}" v-on:click="selectIcon('/img/icons/syphon')">
            <img class="new-icon" src="/img/icons/syphon.svg"/>
            Syphon
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/take-away'}" v-on:click="selectIcon('/img/icons/take-away')">
            <img class="new-icon" src="/img/icons/take-away.svg"/>
            Take Away
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/tea-bag-cup'}" v-on:click="selectIcon('/img/icons/tea-bag-cup')">
            <img class="new-icon" src="/img/icons/tea-bag-cup.svg"/>
            Tea Bag Cup
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/tea-bag'}" v-on:click="selectIcon('/img/icons/tea-bag')">
            <img class="new-icon" src="/img/icons/tea-bag.svg"/>
            Tea Bag
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/tea-ceremony'}" v-on:click="selectIcon('/img/icons/tea-ceremony')">
            <img class="new-icon" src="/img/icons/tea-ceremony.svg"/>
            Tea Ceremony
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/turkish-gezve'}" v-on:click="selectIcon('/img/icons/turkish-gezve')">
            <img class="new-icon" src="/img/icons/turkish-gezve.svg"/>
            Turkish Gezve
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/vacuum-pot'}" v-on:click="selectIcon('/img/icons/vacuum-pot')">
            <img class="new-icon" src="/img/icons/vacuum-pot.svg"/>
            Vacuum Pot
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/vending-machine'}" v-on:click="selectIcon('/img/icons/vending-machine')">
            <img class="new-icon" src="/img/icons/vending-machine.svg"/>
            Vending Machine
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/vietnamese-hot'}" v-on:click="selectIcon('/img/icons/vietnamese-hot')">
            <img class="new-icon" src="/img/icons/vietnamese-hot.svg"/>
            Vietnamese Hot
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/vintage-coffee-pot'}" v-on:click="selectIcon('/img/icons/vintage-coffee-pot')">
            <img class="new-icon" src="/img/icons/vintage-coffee-pot.svg"/>
            Vintage Coffee Pot
          </div>
          <div class="large-3 medium-3 new-icon-container cell" v-bind:class="{'active': icon == '/img/icons/wifi'}" v-on:click="selectIcon('/img/icons/wifi')">
            <img class="new-icon" src="/img/icons/wifi.svg"/>
            Wifi
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the event bus to emit events.
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    data(){
      return {
        method: '',
        icon: '',

        showChangeIcon: false,

        validations: {
          method: true
        }
      }
    },

    /*
      Sets up the page when created.
    */
    created(){
      this.$store.dispatch( 'loadAdminBrewMethod', {
        id: this.$route.params.id
      });
    },

    /*
      Defines the computed properties on the page.
    */
    computed: {
      /*
        Get the brew method from the Vuex data store.
      */
      brewMethod(){
        return this.$store.getters.getAdminBrewMethod;
      },

      /*
        Get the brew method load status from the Vuex data store.
      */
      brewMethodLoadStatus(){
        return this.$store.getters.getAdminBrewMethodLoadStatus;
      },

      /*
        Get the brew method update status from the Vuex data store.
      */
      brewMethodUpdateStatus(){
        return this.$store.getters.getAdminBrewMethodUpdateStatus;
      }
    },

    /*
      Defines the watchers on the page.
    */
    watch: {
      /*
        Watch the brew method load status.
      */
      'brewMethodLoadStatus': function(){
        /*
          When the status is 2, meaning it's been loaded
          correctly, sync the brew method to the model.
        */
        if( this.brewMethodLoadStatus == 2 ){
          this.syncBrewMethodToModel();
        }
      },

      /*
        Watch the brew method update status.
      */
      'brewMethodUpdateStatus': function(){
        /*
          When the status is 2, meaning the brew method has
          been updated, sync the brew method to the model and
          show a notification.
        */
        if( this.brewMethodUpdateStatus == 2 ){
          this.syncBrewMethodToModel();

          EventBus.$emit('show-success', {
            notification: 'Brew method updated successfully!'
          });
        }
      }
    },

    /*
      Defines the methods used by the page.
    */
    methods: {
      /*
        Syncs the brew method to the model.
      */
      syncBrewMethodToModel(){
        this.method = this.brewMethod.method;
        this.icon = this.brewMethod.icon;
      },

      /*
        Sends the request to update the brew method.
      */
      updateBrewMethod(){
        if( this.validateEditBrewMethod() ){
          this.$store.dispatch( 'updateAdminBrewMethod', {
            id: this.brewMethod.id,
            method: this.method,
            icon: this.icon
          });
        }
      },

      /*
        Validates that all fields are set before submitting
        updates.
      */
      validateEditBrewMethod(){
        let validEditBrewMethodForm = true;

        /*
          If the method is empty, we flag the field as invalid.
        */
        if( this.method.trim() == '' ){
          validEditBrewMethodForm = false;
          this.validations.method = false;
        }else{
          this.validations.method = true;
        }

        return validEditBrewMethodForm;
      },

      /*
        Shows the change icon model
      */
      promptChangeIcon(){
        this.showChangeIcon = true;
      },

      /*
        Hides the change icon modal
      */
      hideChangeIcon(){
        this.showChangeIcon = false;
      },

      /*
        Selects a new icon
      */
      selectIcon( url ){
        this.icon = url;
        this.hideChangeIcon();
      }
    }
  }
</script>
