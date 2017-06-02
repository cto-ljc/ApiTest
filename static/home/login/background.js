var width = window.innerWidth - 2;
var height = window.innerHeight - 2;

// create an new instance of a pixi stage
var stage = new PIXI.Stage(0x0, true);

// create a renderer instance
var renderer = PIXI.autoDetectRenderer(width, height);

// add the renderer view element to the DOM
document.body.appendChild(renderer.view);

// smoke shader
var uniforms = {};
uniforms.resolution = {
	type: '2f',
	value: {
		x: width,
		y: height
	}
};
uniforms.alpha = {
	type: '1f',
	value: 1.0
};
uniforms.shift = {
	type: '1f',
	value: 1.6
};
uniforms.time = {
	type: '1f',
	value: 0
};
uniforms.speed = {
	type: '2f',
	value: {
		x: 0.7,
		y: 0.4
	}
};

var fragmentSrc = [
	"precision mediump float;",
	"uniform vec2      resolution;",
	"uniform float     time;",
	"uniform float     alpha;",
	"uniform vec2      speed;",
	"uniform float     shift;",
	
	"float rand(vec2 n) {",
		"return fract(cos(dot(n, vec2(12.9898, 4.1414))) * 43758.5453);",
	"}",
	
	"float noise(vec2 n) {",
		"const vec2 d = vec2(0.0, 1.0);",
		"vec2 b = floor(n), f = smoothstep(vec2(0.0), vec2(1.0), fract(n));",
		"return mix(mix(rand(b), rand(b + d.yx), f.x), mix(rand(b + d.xy), rand(b + d.yy), f.x), f.y);",
	"}",
	
	"float fbm(vec2 n) {",
		"float total = 0.0, amplitude = 1.0;",
		"for (int i = 0; i < 4; i++) {",
			"total += noise(n) * amplitude;",
			"n += n;",
			"amplitude *= 0.5;",
		"}",
		"return total;",
	"}",
	
	"void main() {",
	
		"const vec3 c1 = vec3(126.0/255.0, 0.0/255.0, 97.0/255.0);",
		"const vec3 c2 = vec3(173.0/255.0, 0.0/255.0, 161.4/255.0);",
		"const vec3 c3 = vec3(0.2, 0.0, 0.0);",
		"const vec3 c4 = vec3(164.0/255.0, 1.0/255.0, 214.4/255.0);",
		"const vec3 c5 = vec3(0.1);",
		"const vec3 c6 = vec3(0.9);",

		"vec2 p = gl_FragCoord.xy * 8.0 / resolution.xx;",
		"float q = fbm(p - time * 0.1);",
		"vec2 r = vec2(fbm(p + q + time * speed.x - p.x - p.y), fbm(p + q - time * speed.y));",
		"vec3 c = mix(c1, c2, fbm(p + r)) + mix(c3, c4, r.x) - mix(c5, c6, r.y);",
		"float grad = gl_FragCoord.y / resolution.y;",
		"gl_FragColor = vec4(c * cos(shift * gl_FragCoord.y / resolution.y), 1.0);",
		"gl_FragColor.xyz *= 1.0-grad;",
	"}"
];

var coolFilter = new PIXI.AbstractFilter(fragmentSrc, uniforms);

var bg = PIXI.Sprite.fromImage("http://www.goodboydigital.com/pixijs/examples/25/test_BG.jpg");
bg.width = width;
bg.height = height;
bg.shader = coolFilter;
stage.addChild(bg);
var count = 0;

function animate() {
	count += 0.01;
	
	coolFilter.uniforms.time.value = count;
	coolFilter.syncUniforms();
		
	renderer.render(stage);
				
	requestAnimFrame(animate);
}

requestAnimFrame(animate);