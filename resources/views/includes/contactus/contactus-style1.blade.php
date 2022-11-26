

    <div class="container-fuild">
        <nav aria-label="breadcrumb">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./">{{ trans('lables.bread-crumb-home') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="./">{{ trans('lables.bread-contact') }}</a>
                    </li>
                </ol>
            </div>
        </nav>
    </div>
    <section class="pro-content">
        <div class="container">
            <div class="page-heading-title">
                <h2> {{ trans('lables.bread-contact') }}
                </h2>

            </div>
        </div>

        <!-- contact Content -->
        <section class="contact-content contact-two-content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="row">
                            <div class="col-12 col-lg-4 contact-main">
                                
                                        <ul class="contact-info pl-0 mb-0">
                                            <li> <i class="fas fa-mobile-alt"></i><span>{{isset(getSetting()['phone_number']) ? getSetting()['phone_number'] : '' }}</span> </li>
                                            <li> <i class="fas fa-map-marker"></i><span>{{isset(getSetting()['address']) ? getSetting()['address'] : '' }}</span> </li>
                                            <li> <i class="fas fa-envelope"></i><span><a href="mailto:{{isset(getSetting()['email']) ? getSetting()['email'] : '#' }}">{{isset(getSetting()['email']) ? getSetting()['email'] : '' }}</a></span> </li>
                                        </ul>
                                    

                                
                            </div>
                            
                            <div class="col-12 col-lg-2"></div>
                            <div class="col-12 col-lg-6">

                                <div class="">
                                    <form id="contactusForm">
                                        <div class="row">
                                            <div class="from-group mb-3 col-12">
                                                <label for="inlineFormInputGroup">{{ trans('lables.contact-fname') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="first_name" placeholder="{{ trans('lables.contact-fname') }}">
                                                </div>
                                                <small class="first_name errors d-none" style="color:red"></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="from-group mb-3 col-12">
                                                <label for="inlineFormInputGroup">{{ trans('lables.contact-lname') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="last_name" placeholder="{{ trans('lables.contact-lname') }}">
                                                </div>
                                                <small class="last_name errors d-none" style="color:red"></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="from-group mb-3 col-12">
                                                <label for="inlineFormInputGroup">{{ trans('lables.contact-email') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="email" placeholder="{{ trans('lables.contact-email') }}">
                                                </div>
                                                <small class="email errors d-none" style="color:red"></small>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="row">
                                            <div class="from-group mb-3 col-12">
                                                <label for="inlineFormInputGroup">{{ trans('lables.contact-phone') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="phone" placeholder="{{ trans('lables.contact-phone') }}">
                                                </div>
                                                <small class="phone errors d-none" style="color:red"></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="from-group mb-3 col-12">
                                                <label for="inlineFormInputGroup">{{ trans('lables.contact-message') }}</label>
                                                <div class="">
                                                    <textarea class="form-control" id="message" placeholder="{{ trans('lables.contact-message') }}" rows="5"
                                            cols=""></textarea>
                                                </div>
                                                <small class="message errors d-none" style="color:red"></small>
                                            </div>
                                        </div>

                                        


                                        <button type="submit" class="btn btn-secondary swipe-to-top">SUBMIT <i
                                                class="fas fa-location-arrow"></i></button>
                                    </form>
                                </div>
                            </div>
                 
                     
                  </div>
                </div>
              </div>
              
            </div>      
          </section>
          </section>
