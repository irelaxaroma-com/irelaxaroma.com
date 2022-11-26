<template>
<div class="form-group row">
    <div class="col-md-6">
        <label>About Bussiness</label>
        <fieldset class="form-group mb-3">
            <textarea type="text" class="form-control border-dark" cols="12" rows="12" v-model="general.about_us"> </textarea>
        </fieldset>
    </div>
    <div class="col-md-6">
        <label>Address</label>
        <fieldset class="form-group mb-3">
            <textarea type="text" class="form-control border-dark" cols="12" rows="12" v-model="general.address"> </textarea>
        </fieldset>    
    </div>

	<div class="col-md-6">
        <label>Buiness Name</label>
        <fieldset class="form-group mb-3">
            <input type="text" class="form-control border-dark" placeholder="" v-model="general.business_name" ref="smtp_port">
        </fieldset>
    </div>

    <div class="col-md-6">
        <label>Phone Number</label>
        <fieldset class="form-group mb-3">
            <input type="text" class="form-control border-dark" placeholder="" v-model="general.phone_number" ref="smtp_port">
        </fieldset>
    </div>

	<div class="col-md-6">
        <label>Business Email</label>
        <fieldset class="form-group mb-3">
            <input type="email" class="form-control border-dark" placeholder="" v-model="general.email" ref="smtp_encription">
        </fieldset>
    </div>

    <div class="col-md-6">
        <label>New Badge Visibilty Time On Product Carts</label>
        <fieldset class="form-group mb-3">
            <input type="number" class="form-control border-dark" placeholder="" v-model="general.new_bage_on_product_card_visibility" ref="new_bage_on_product_card_visibility">
        </fieldset>
    </div>

    <div class="col-md-6">
        <label>Minimum Order Total</label>
        <fieldset class="form-group mb-3">
            <input type="number" class="form-control border-dark" placeholder="" v-model="general.minimum_order_total" ref="minimum_order_total">
        </fieldset>
    </div>

    <div class="col-md-6">
        <label>Minimum Order Price for Free Shipping</label>
        <fieldset class="form-group mb-3">
            <input type="number" class="form-control border-dark" placeholder="" v-model="general.free_shipping_order_price" ref="free_shipping_order_price">
        </fieldset>
    </div>

	
    <div class="col-md-12">
		<br />
        <button @click="updateSetting()" type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
</template>

<script>
import ErrorHandling from './../../../ErrorHandling'
export default {
    data() {
        return {
            general: {
                about_us:"",
                address:"",
                business_name:"",
                phone_number:"",
                email:"",
                new_bage_on_product_card_visibility:"",
                minimum_order_total:"",
                free_shipping_order_price:""
            },
            errors: new ErrorHandling(),
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        };
    },

    methods: {
        fetchSetting() {
            this.$emit('updateLoadingState', true);
            var token = localStorage.getItem('token');
            const config = {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            };
            var responseData = {};

            axios.get('/api/admin/setting?type=business_general', config)
                .then(res => {
                    for (var i = 0; i < res.data.data.length; i++) {
                        Object.assign(responseData, {
                            [res.data.data[i].setting_key]: res.data.data[i].setting_value
                        });
                    }
                    console.log('response datass', responseData);
                    this.general = responseData;
                })
                .finally(() => (this.$emit('updateLoadingState', false)));
        },

        updateSetting() {
            this.$emit('updateLoadingState', true);
            var general = Object.entries(this.general);
            var key = [];
            var value = [];

            for (var i = 0; i < general.length; i++) {
                key.push(general[i][0]);
                value.push(general[i][1])
            }
            

            // console.log(key, value);

            var token = localStorage.getItem('token');
            const config = {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            };

            axios.post('/api/admin/setting/business_general', {
                    _method: 'PUT',
                    key,
                    value
                }, config)
                .then(res => {
                    if (res.data.status == "Success") {
						this.$toaster.success('Settings has been updated successfully')
                    }
                    else if(res.data.status == 'Error'){
                        this.$toaster.error(res.data.message)
                    }
                    
                })
                .catch(err => {
                    if(err.response.data.status == 'Error'){
                        this.$toaster.error(err.response.data.message)
                    }
                })
                .finally(() => (this.$emit('updateLoadingState', false)));

        }
    },
    mounted() {
        this.fetchSetting();
    }
};
</script>
