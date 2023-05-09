<script setup lang="ts">
import { onMounted } from "vue";

onMounted(() => {
  
});
</script>

<template>
    <div class="mdc-layout-grid">
      <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
          <div class="mdc-card table-responsive">
            <div class="table-heading px-2 px-1 border-bottom">
              <h1 class="mdc-card__title mdc-card__title--large">For Edit</h1>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left">Product</th>
                  <th>Cost</th>
                  <th>Sales amount</th>
                  <th>Shipping cost</th>
                  <th>Units sold</th>
                  <th>Profit generated</th>
                  <th>Left in stock</th>
                  <th>Returns</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">T-shirts</td>
                  <td>250</td>
                  <td>300</td>
                  <td>60</td>
                  <td>3453</td>
                  <td>76</td>
                  <td>453643</td>
                  <td>300</td>
                  <td><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-heart text-blue"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-forum text-yellow"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-delete text-red"></i></div></td>
                </tr>
                <tr>
                  <td class="text-left">Baseball Hat</td>
                  <td>457</td>
                  <td>204</td>
                  <td>35</td>
                  <td>6754</td>
                  <td>35</td>
                  <td>345623</td>
                  <td>546</td>
                  <td><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-heart text-blue"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-forum text-yellow"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-delete text-red"></i></div></td>
                </tr>
                <tr>
                  <td class="text-left">Tennis Racket</td>
                  <td>250</td>
                  <td>350</td>
                  <td>38</td>
                  <td>3289</td>
                  <td>45</td>
                  <td>54662</td>
                  <td>278</td>
                  <td><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-heart text-blue"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-forum text-yellow"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-delete text-red"></i></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="mdc-layout-grid">
      <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
          <div class="mdc-card table-responsive">
            <div class="table-heading px-2 px-1 border-bottom">
              <h1 class="mdc-card__title mdc-card__title--large">Published</h1>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left">Product</th>
                  <th>Cost</th>
                  <th>Sales amount</th>
                  <th>Shipping cost</th>
                  <th>Units sold</th>
                  <th>Profit generated</th>
                  <th>Left in stock</th>
                  <th>Returns</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-left">T-shirts</td>
                  <td>250</td>
                  <td>300</td>
                  <td>60</td>
                  <td>3453</td>
                  <td>76</td>
                  <td>453643</td>
                  <td>300</td>
                  <td><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-heart text-blue"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-forum text-yellow"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-delete text-red"></i></div></td>
                </tr>
                <tr>
                  <td class="text-left">Baseball Hat</td>
                  <td>457</td>
                  <td>204</td>
                  <td>35</td>
                  <td>6754</td>
                  <td>35</td>
                  <td>345623</td>
                  <td>546</td>
                  <td><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-heart text-blue"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-forum text-yellow"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-delete text-red"></i></div></td>
                </tr>
                <tr>
                  <td class="text-left">Tennis Racket</td>
                  <td>250</td>
                  <td>350</td>
                  <td>38</td>
                  <td>3289</td>
                  <td>45</td>
                  <td>54662</td>
                  <td>278</td>
                  <td><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-heart text-blue"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-forum text-yellow"></i></div><div class="col mdc-button" data-mdc-auto-init="MDCRipple"><i class="mdi mdi-delete text-red"></i></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</template>