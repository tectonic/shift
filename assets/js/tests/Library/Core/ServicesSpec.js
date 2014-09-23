'use strict';

describe('module: Shift.Library.Core.Services', function() {
	beforeEach(module('Shift.Library.Core.Services'));

	describe('service: Config', function() {
		var Config;

		beforeEach(inject(function(_Config_) {
			Config = _Config_;
		}));

		it('should add new configuration keys and values', function() {
			expect(Config.add('something', 'value')).toBeUndefined();
			expect(Config.get('something')).toEqual('value');
		});

		it('should be able to hydrate the configuration with a json object of options', function() {
			var options = {
				test: 'value',
				config: 'option'
			};

			Config.hydrate(options);

			expect(Config.get('test')).toEqual('value');
			expect(Config.get('config')).toEqual('option');
		});

		it('should be able to return all options', function() {
			var options = {
				testing: 'all',
				options: 'hydrated'
			};

			Config.hydrate(options);

			expect(Config.all()).toEqual(options);
		});

		it('should be able to hydrate the service with a base64 string', function() {
			Config.hydrateBase64('eyJ1c2VyIjogeyJuYW1lIjogIktpcmsifSwgImFjY291bnQiOiAiNTQ2NzMyMyJ9');

			expect(Config.all()).toEqual({"user": {"name": "Kirk"}, "account": "5467323"});
		});
	});

	describe('service: DateTimeFormats', function() {
		var DateTimeFormats;

		beforeEach(inject(function(_DateTimeFormats_) {
			DateTimeFormats = _DateTimeFormats_;
		}));

		it('should have the correct date format', function() {
			expect(DateTimeFormats.dateFormat).toBe('YYYY-MM-DD');
		});

		it('should have the correct time format', function() {
			expect(DateTimeFormats.timeFormat).toBe('HH:mm:ss');
		});

		it('should have the correct server datetime format', function() {
			expect(DateTimeFormats.serverFormat).toBe('YYYY-MM-DD HH:mm:ss');
		});

		it('should have the correct server datetime format', function() {
			expect(DateTimeFormats.clientFormat).toBe('YYYY-MM-DD HH:mm');
		});
	});

    describe('service: Language', function() {

        var Language;

        beforeEach(inject(function(_Language_){
            Language = _Language_;
        }));

        it('should return the correct error string', function() {
            expect(Language.errorString).toBe('ERROR: TRANSLATION NOT FOUND!');
        });

        it('should return the correct language item', function() {
            var dictionary = {
                shift: {
                    lang: {
                        en_GB: {
                            labels: {
                                first_name: 'John',
                                last_name: 'Smith',
                                colour: 'colour',
                                button: 'button'
                            }
                        },
                        en_US: {
                            labels: {
                                colour: 'color'
                            }
                        },
                        fr_FR: {
                            labels: {
                                button: 'bouton'
                            }
                        }
                    }
                }
            };

            // User specific translation preference is 'fr_FR'
            // Installation specific translation preference is 'en_US'
            // Base/default translation is 'en_GB'
            var locales = ['fr_FR', 'en_US', 'en_GB'];

            expect(Language.find(dictionary, locales, 'shift', 'labels.first_name')).toBe('John');
            expect(Language.find(dictionary, locales, 'shift', 'labels.last_name')).toBe('Smith');
            expect(Language.find(dictionary, locales, 'shift', 'labels.colour')).toBe('color');
            expect(Language.find(dictionary, locales, 'shift', 'labels.button')).toBe('bouton');
            expect(Language.find(dictionary, locales, 'shift', 'labels.age')).toBe(Language.errorString);
        });

    });

	describe('service: Resource', function() {
		var Resource, $mockRestangular, $mockServiceModel;
		
		beforeEach(function() {
			$mockRestangular = function() {};
			$mockServiceModel = {};

			$mockRestangular.all = jasmine.createSpy('all').andReturn($mockServiceModel);

			module('Shift.Library.Core.Services', function($provide) {
				$provide.value('Restangular', $mockRestangular);
			});

			inject(function(_Resource_) {
				Resource = _Resource_;
			});
		});

		it('should instantiate a restangular resource', function() {
			new Resource('users');

			expect($mockRestangular.all).toHaveBeenCalled();
		});

		it('should create an item', function() {
			$mockServiceModel.post = jasmine.createSpy('post');

			var user = new Resource('users');
			var params = {name: 'Kirk'};

			user.create(params);

			expect($mockServiceModel.post).toHaveBeenCalledWith(params);
		});

		it('should destroy an item', function() {
			var item = {remove: function(){}};
			var user = new Resource('users');

			spyOn(item, 'remove');

			user.destroy(item);

			expect(item.remove).toHaveBeenCalled();
		});

		it('should get an existing item', function() {
			$mockServiceModel.get = jasmine.createSpy('get');

			var resource = new Resource('users');

			resource.get(1);

			expect($mockServiceModel.get).toHaveBeenCalledWith(1);
		});

		it('should get all items', function() {
			$mockServiceModel.getList = jasmine.createSpy('getList');

			var resource = new Resource('users');

			resource.all();

			expect($mockServiceModel.getList).toHaveBeenCalled();
		});

		it('should update an existing item', function() {
			var item = {put: function(){}};
			var user = new Resource('users');

			spyOn(item, 'put');

			user.update(item);

			expect(item.put).toHaveBeenCalled();
		});

		it('should save a new item', function() {
			$mockServiceModel.post = jasmine.createSpy('post');

			var resource = new Resource('users');
			var params = {name: 'Kirk'};

			resource.save(params);

			expect($mockServiceModel.post).toHaveBeenCalledWith(params);
		});

		it('should save an existing item', function() {
			var item = {id: 1, name: 'Kirk', put: function(){}};
			var resource = new Resource('users');

			spyOn(item, 'put');

			resource.save(item);

			expect(item.put).toHaveBeenCalled();
		});
	});
});
