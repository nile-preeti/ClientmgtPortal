! function(t, e) { "function" == typeof define && define.amd ? define(e) : "object" == typeof module && module.exports ? module.exports = e() : t.bodymovin = e() }(window, function() {
    function ProjectInterface() { return {} }

    function roundValues(t) { bm_rnd = t ? Math.round : function(t) { return t } }

    function roundTo2Decimals(t) { return Math.round(1e4 * t) / 1e4 }

    function roundTo3Decimals(t) { return Math.round(100 * t) / 100 }

    function styleDiv(t) { t.style.position = "absolute", t.style.top = 0, t.style.left = 0, t.style.display = "block", t.style.transformOrigin = t.style.webkitTransformOrigin = "0 0", t.style.backfaceVisibility = t.style.webkitBackfaceVisibility = "visible", t.style.transformStyle = t.style.webkitTransformStyle = t.style.mozTransformStyle = "preserve-3d" }

    function styleUnselectableDiv(t) { t.style.userSelect = "none", t.style.MozUserSelect = "none", t.style.webkitUserSelect = "none", t.style.oUserSelect = "none" }

    function BMEnterFrameEvent(t, e, r, s) { this.type = t, this.currentTime = e, this.totalTime = r, this.direction = 0 > s ? -1 : 1 }

    function BMCompleteEvent(t, e) { this.type = t, this.direction = 0 > e ? -1 : 1 }

    function BMCompleteLoopEvent(t, e, r, s) { this.type = t, this.currentLoop = e, this.totalLoops = r, this.direction = 0 > s ? -1 : 1 }

    function BMSegmentStartEvent(t, e, r) { this.type = t, this.firstFrame = e, this.totalFrames = r }

    function BMDestroyEvent(t, e) { this.type = t, this.target = e }

    function _addEventListener(t, e) { this._cbs[t] || (this._cbs[t] = []), this._cbs[t].push(e) }

    function _removeEventListener(t, e) { if (e) { if (this._cbs[t]) { for (var r = 0, s = this._cbs[t].length; s > r;) this._cbs[t][r] === e && (this._cbs[t].splice(r, 1), r -= 1, s -= 1), r += 1;
                this._cbs[t].length || (this._cbs[t] = null) } } else this._cbs[t] = null }

    function _triggerEvent(t, e) { if (this._cbs[t])
            for (var r = this._cbs[t].length, s = 0; r > s; s++) this._cbs[t][s](e) }

    function randomString(t, e) { void 0 === e && (e = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"); var r, s = ""; for (r = t; r > 0; --r) s += e[Math.round(Math.random() * (e.length - 1))]; return s }

    function HSVtoRGB(t, e, r) { var s, i, a, n, o, h, l, p; switch (1 === arguments.length && (e = t.s, r = t.v, t = t.h), n = Math.floor(6 * t), o = 6 * t - n, h = r * (1 - e), l = r * (1 - o * e), p = r * (1 - (1 - o) * e), n % 6) {
            case 0:
                s = r, i = p, a = h; break;
            case 1:
                s = l, i = r, a = h; break;
            case 2:
                s = h, i = r, a = p; break;
            case 3:
                s = h, i = l, a = r; break;
            case 4:
                s = p, i = h, a = r; break;
            case 5:
                s = r, i = h, a = l } return [s, i, a] }

    function RGBtoHSV(t, e, r) { 1 === arguments.length && (e = t.g, r = t.b, t = t.r); var s, i = Math.max(t, e, r),
            a = Math.min(t, e, r),
            n = i - a,
            o = 0 === i ? 0 : n / i,
            h = i / 255; switch (i) {
            case a:
                s = 0; break;
            case t:
                s = e - r + n * (r > e ? 6 : 0), s /= 6 * n; break;
            case e:
                s = r - t + 2 * n, s /= 6 * n; break;
            case r:
                s = t - e + 4 * n, s /= 6 * n } return [s, o, h] }

    function addSaturationToRGB(t, e) { var r = RGBtoHSV(255 * t[0], 255 * t[1], 255 * t[2]); return r[1] += e, r[1] > 1 ? r[1] = 1 : r[1] <= 0 && (r[1] = 0), HSVtoRGB(r[0], r[1], r[2]) }

    function addBrightnessToRGB(t, e) { var r = RGBtoHSV(255 * t[0], 255 * t[1], 255 * t[2]); return r[2] += e, r[2] > 1 ? r[2] = 1 : r[2] < 0 && (r[2] = 0), HSVtoRGB(r[0], r[1], r[2]) }

    function addHueToRGB(t, e) { var r = RGBtoHSV(255 * t[0], 255 * t[1], 255 * t[2]); return r[0] += e / 360, r[0] > 1 ? r[0] -= 1 : r[0] < 0 && (r[0] += 1), HSVtoRGB(r[0], r[1], r[2]) }

    function componentToHex(t) { var e = t.toString(16); return 1 == e.length ? "0" + e : e }

    function fillToRgba(t, e) { if (!cachedColors[t]) { var r = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t);
            cachedColors[t] = parseInt(r[1], 16) + "," + parseInt(r[2], 16) + "," + parseInt(r[3], 16) } return "rgba(" + cachedColors[t] + "," + e + ")" }

    function RenderedFrame(t, e) { this.tr = t, this.o = e }

    function LetterProps(t, e, r, s, i, a) { this.o = t, this.sw = e, this.sc = r, this.fc = s, this.m = i, this.props = a }

    function iterateDynamicProperties(t) { var e, r = this.dynamicProperties; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(t) }

    function reversePath(t) { var e, r, s = [],
            i = [],
            a = [],
            n = {},
            o = 0;
        t.c && (s[0] = t.o[0], i[0] = t.i[0], a[0] = t.v[0], o = 1), r = t.i.length; var h = r - 1; for (e = o; r > e; e += 1) s.push(t.o[h]), i.push(t.i[h]), a.push(t.v[h]), h -= 1; return n.i = s, n.o = i, n.v = a, n }

    function Matrix() {}

    function matrixManagerFunction() { var t = new Matrix,
            e = function(e, r, s, i, a) { return t.reset().translate(i, a).rotate(e).scale(r, s).toCSS() },
            r = function(t) { return e(t.tr.r[2], t.tr.s[0], t.tr.s[1], t.tr.p[0], t.tr.p[1]) }; return { getMatrix: r } }

    function createElement(t, e, r) { if (!e) { var s = Object.create(t.prototype, r),
                i = {}; return s && "[object Function]" === i.toString.call(s.init) && s.init(), s }
        e.prototype = Object.create(t.prototype), e.prototype.constructor = e, e.prototype._parent = t.prototype }

    function extendPrototype(t, e) { for (var r in t.prototype) t.prototype.hasOwnProperty(r) && (e.prototype[r] = t.prototype[r]) }

    function bezFunction() {
        function t(t, e, r, s, i, a) { var n = t * s + e * i + r * a - i * s - a * t - r * e; return n > -1e-4 && 1e-4 > n }

        function e(e, r, s, i, a, n, o, h, l) { return t(e, r, i, a, o, h) && t(e, s, i, n, o, l) }

        function r(t) { this.segmentLength = 0, this.points = new Array(t) }

        function s(t, e) { this.partialLength = t, this.point = e }

        function i(t, e) { var r = e.segments,
                s = r.length,
                i = bm_floor((s - 1) * t),
                a = t * e.addedLength,
                n = 0; if (a == r[i].l) return r[i].p; for (var o = r[i].l > a ? -1 : 1, h = !0; h;) r[i].l <= a && r[i + 1].l > a ? (n = (a - r[i].l) / (r[i + 1].l - r[i].l), h = !1) : i += o, (0 > i || i >= s - 1) && (h = !1); return r[i].p + (r[i + 1].p - r[i].p) * n }

        function a() { this.pt1 = new Array(2), this.pt2 = new Array(2), this.pt3 = new Array(2), this.pt4 = new Array(2) }

        function n(t, e, r, s, n, o, h) { var l = new a;
            n = 0 > n ? 0 : n > 1 ? 1 : n; var p = i(n, h);
            o = o > 1 ? 1 : o; var m, f = i(o, h),
                c = t.length,
                d = 1 - p,
                u = 1 - f; for (m = 0; c > m; m += 1) l.pt1[m] = Math.round(1e3 * (d * d * d * t[m] + (p * d * d + d * p * d + d * d * p) * r[m] + (p * p * d + d * p * p + p * d * p) * s[m] + p * p * p * e[m])) / 1e3, l.pt3[m] = Math.round(1e3 * (d * d * u * t[m] + (p * d * u + d * p * u + d * d * f) * r[m] + (p * p * u + d * p * f + p * d * f) * s[m] + p * p * f * e[m])) / 1e3, l.pt4[m] = Math.round(1e3 * (d * u * u * t[m] + (p * u * u + d * f * u + d * u * f) * r[m] + (p * f * u + d * f * f + p * u * f) * s[m] + p * f * f * e[m])) / 1e3, l.pt2[m] = Math.round(1e3 * (u * u * u * t[m] + (f * u * u + u * f * u + u * u * f) * r[m] + (f * f * u + u * f * f + f * u * f) * s[m] + f * f * f * e[m])) / 1e3; return l } var o = (Math, function() {
                function t(t, e) { this.l = t, this.p = e } return function(e, r, s, i) { var a, n, o, h, l, p, m = defaultCurveSegments,
                        f = 0,
                        c = [],
                        d = [],
                        u = { addedLength: 0, segments: [] }; for (o = s.length, a = 0; m > a; a += 1) { for (l = a / (m - 1), p = 0, n = 0; o > n; n += 1) h = bm_pow(1 - l, 3) * e[n] + 3 * bm_pow(1 - l, 2) * l * s[n] + 3 * (1 - l) * bm_pow(l, 2) * i[n] + bm_pow(l, 3) * r[n], c[n] = h, null !== d[n] && (p += bm_pow(c[n] - d[n], 2)), d[n] = c[n];
                        p && (p = bm_sqrt(p), f += p), u.segments.push(new t(f, l)) } return u.addedLength = f, u } }()),
            h = function() { var e = {}; return function(i) { var a = i.s,
                        n = i.e,
                        o = i.to,
                        h = i.ti,
                        l = (a.join("_") + "_" + n.join("_") + "_" + o.join("_") + "_" + h.join("_")).replace(/\./g, "p"); if (e[l]) return void(i.bezierData = e[l]); var p, m, f, c, d, u, y, g = defaultCurveSegments,
                        v = 0,
                        b = null;
                    2 === a.length && (a[0] != n[0] || a[1] != n[1]) && t(a[0], a[1], n[0], n[1], a[0] + o[0], a[1] + o[1]) && t(a[0], a[1], n[0], n[1], n[0] + h[0], n[1] + h[1]) && (g = 2); var E = new r(g); for (f = o.length, p = 0; g > p; p += 1) { for (y = new Array(f), d = p / (g - 1), u = 0, m = 0; f > m; m += 1) c = bm_pow(1 - d, 3) * a[m] + 3 * bm_pow(1 - d, 2) * d * (a[m] + o[m]) + 3 * (1 - d) * bm_pow(d, 2) * (n[m] + h[m]) + bm_pow(d, 3) * n[m], y[m] = c, null !== b && (u += bm_pow(y[m] - b[m], 2));
                        u = bm_sqrt(u), v += u, E.points[p] = new s(u, y), b = y }
                    E.segmentLength = v, i.bezierData = E, e[l] = E } }(); return { getBezierLength: o, getNewSegment: n, buildBezierData: h, pointOnLine2D: t, pointOnLine3D: e } }

    function dataFunctionManager() {
        function t(i, a, o) { var h, l, p, m, f, c, d, u, y = i.length; for (m = 0; y > m; m += 1)
                if (h = i[m], "ks" in h && !h.completed) { if (h.completed = !0, h.tt && (i[m - 1].td = h.tt), l = [], p = -1, h.hasMask) { var g = h.masksProperties; for (c = g.length, f = 0; c > f; f += 1)
                            if (g[f].pt.k.i) s(g[f].pt.k);
                            else
                                for (u = g[f].pt.k.length, d = 0; u > d; d += 1) g[f].pt.k[d].s && s(g[f].pt.k[d].s[0]), g[f].pt.k[d].e && s(g[f].pt.k[d].e[0]) }
                    0 === h.ty ? (h.layers = e(h.refId, a), t(h.layers, a, o)) : 4 === h.ty ? r(h.shapes) : 5 == h.ty && n(h, o) } }

        function e(t, e) { for (var r = 0, s = e.length; s > r;) { if (e[r].id === t) return e[r].layers;
                r += 1 } }

        function r(t) { var e, i, a, n = t.length,
                o = !1; for (e = n - 1; e >= 0; e -= 1)
                if ("sh" == t[e].ty) { if (t[e].ks.k.i) s(t[e].ks.k);
                    else
                        for (a = t[e].ks.k.length, i = 0; a > i; i += 1) t[e].ks.k[i].s && s(t[e].ks.k[i].s[0]), t[e].ks.k[i].e && s(t[e].ks.k[i].e[0]);
                    o = !0 } else "gr" == t[e].ty && r(t[e].it) }

        function s(t) { var e, r = t.i.length; for (e = 0; r > e; e += 1) t.i[e][0] += t.v[e][0], t.i[e][1] += t.v[e][1], t.o[e][0] += t.v[e][0], t.o[e][1] += t.v[e][1] }

        function i(t, e) { var r = e ? e.split(".") : [100, 100, 100]; return t[0] > r[0] ? !0 : r[0] > t[0] ? !1 : t[1] > r[1] ? !0 : r[1] > t[1] ? !1 : t[2] > r[2] ? !0 : r[2] > t[2] ? !1 : void 0 }

        function a(e, r) { e.__complete || (h(e), o(e), l(e), t(e.layers, e.assets, r), e.__complete = !0) }

        function n(t, e) { var r, s, i = t.t.d.k,
                a = i.length; for (s = 0; a > s; s += 1) { var n = t.t.d.k[s].s;
                r = []; var o, h, l, p, m, f, c, d = 0,
                    u = t.t.m.g,
                    y = 0,
                    g = 0,
                    v = 0,
                    b = [],
                    E = 0,
                    P = 0,
                    x = e.getFontByName(n.f),
                    S = 0,
                    C = x.fStyle.split(" "),
                    k = "normal",
                    M = "normal"; for (h = C.length, o = 0; h > o; o += 1) "italic" === C[o].toLowerCase() ? M = "italic" : "bold" === C[o].toLowerCase() ? k = "700" : "black" === C[o].toLowerCase() ? k = "900" : "medium" === C[o].toLowerCase() ? k = "500" : "regular" === C[o].toLowerCase() || "normal" === C[o].toLowerCase() ? k = "400" : ("light" === C[o].toLowerCase() || "thin" === C[o].toLowerCase()) && (k = "200"); if (n.fWeight = k, n.fStyle = M, h = n.t.length, n.sz) { var A = n.sz[0],
                        D = -1; for (o = 0; h > o; o += 1) l = !1, " " === n.t.charAt(o) ? D = o : 13 === n.t.charCodeAt(o) && (E = 0, l = !0), e.chars ? (c = e.getCharData(n.t.charAt(o), x.fStyle, x.fFamily), S = l ? 0 : c.w * n.s / 100) : S = e.measureText(n.t.charAt(o), n.f, n.s), E + S > A ? (-1 === D ? (n.t = n.t.substr(0, o) + "\r" + n.t.substr(o), h += 1) : (o = D, n.t = n.t.substr(0, o) + "\r" + n.t.substr(o + 1)), D = -1, E = 0) : E += S;
                    h = n.t.length } for (E = 0, S = 0, o = 0; h > o; o += 1)
                    if (l = !1, " " === n.t.charAt(o) ? p = "\xa0" : 13 === n.t.charCodeAt(o) ? (b.push(E), P = E > P ? E : P, E = 0, p = "", l = !0, v += 1) : p = n.t.charAt(o), e.chars ? (c = e.getCharData(n.t.charAt(o), x.fStyle, e.getFontByName(n.f).fFamily), S = l ? 0 : c.w * n.s / 100) : S = e.measureText(p, n.f, n.s), E += S, r.push({ l: S, an: S, add: y, n: l, anIndexes: [], val: p, line: v }), 2 == u) { if (y += S, "" == p || "\xa0" == p || o == h - 1) { for (("" == p || "\xa0" == p) && (y -= S); o >= g;) r[g].an = y, r[g].ind = d, r[g].extra = S, g += 1;
                            d += 1, y = 0 } } else if (3 == u) { if (y += S, "" == p || o == h - 1) { for ("" == p && (y -= S); o >= g;) r[g].an = y, r[g].ind = d, r[g].extra = S, g += 1;
                        y = 0, d += 1 } } else r[d].ind = d, r[d].extra = 0, d += 1; if (n.l = r, P = E > P ? E : P, b.push(E), n.sz) n.boxWidth = n.sz[0], n.justifyOffset = 0;
                else switch (n.boxWidth = P, n.j) {
                    case 1:
                        n.justifyOffset = -n.boxWidth; break;
                    case 2:
                        n.justifyOffset = -n.boxWidth / 2; break;
                    default:
                        n.justifyOffset = 0 }
                n.lineWidths = b; var T = t.t.a;
                f = T.length; var w, F, I = []; for (m = 0; f > m; m += 1) { for (T[m].a.sc && (n.strokeColorAnim = !0), T[m].a.sw && (n.strokeWidthAnim = !0), (T[m].a.fc || T[m].a.fh || T[m].a.fs || T[m].a.fb) && (n.fillColorAnim = !0), F = 0, w = T[m].s.b, o = 0; h > o; o += 1) r[o].anIndexes[m] = F, (1 == w && "" != r[o].val || 2 == w && "" != r[o].val && "\xa0" != r[o].val || 3 == w && (r[o].n || "\xa0" == r[o].val || o == h - 1) || 4 == w && (r[o].n || o == h - 1)) && (1 === T[m].s.rn && I.push(F), F += 1);
                    t.t.a[m].s.totalChars = F; var _, V = -1; if (1 === T[m].s.rn)
                        for (o = 0; h > o; o += 1) V != r[o].anIndexes[m] && (V = r[o].anIndexes[m], _ = I.splice(Math.floor(Math.random() * I.length), 1)[0]), r[o].anIndexes[m] = _ }
                0 !== f || "m" in t.t.p || (t.singleShape = !0), n.yOffset = n.lh || 1.2 * n.s, n.ascent = x.ascent * n.s / 100 } } var o = function() {
                function t(t) { var e = t.t.d;
                    t.t.d = { k: [{ s: e, t: 0 }] } }

                function e(e) { var r, s = e.length; for (r = 0; s > r; r += 1) 5 === e[r].ty && t(e[r]) } var r = [4, 4, 14]; return function(t) { if (i(r, t.v) && (e(t.layers), t.assets)) { var s, a = t.assets.length; for (s = 0; a > s; s += 1) t.assets[s].layers && e(t.assets[s].layers) } } }(),
            h = function() {
                function t(e) { var r, s, i, a = e.length; for (r = 0; a > r; r += 1)
                        if ("gr" === e[r].ty) t(e[r].it);
                        else if ("fl" === e[r].ty || "st" === e[r].ty)
                        if (e[r].c.k && e[r].c.k[0].i)
                            for (i = e[r].c.k.length, s = 0; i > s; s += 1) e[r].c.k[s].s && (e[r].c.k[s].s[0] /= 255, e[r].c.k[s].s[1] /= 255, e[r].c.k[s].s[2] /= 255, e[r].c.k[s].s[3] /= 255), e[r].c.k[s].e && (e[r].c.k[s].e[0] /= 255, e[r].c.k[s].e[1] /= 255, e[r].c.k[s].e[2] /= 255, e[r].c.k[s].e[3] /= 255);
                        else e[r].c.k[0] /= 255, e[r].c.k[1] /= 255, e[r].c.k[2] /= 255, e[r].c.k[3] /= 255 }

                function e(e) { var r, s = e.length; for (r = 0; s > r; r += 1) 4 === e[r].ty && t(e[r].shapes) } var r = [4, 1, 9]; return function(t) { if (i(r, t.v) && (e(t.layers), t.assets)) { var s, a = t.assets.length; for (s = 0; a > s; s += 1) t.assets[s].layers && e(t.assets[s].layers) } } }(),
            l = function() {
                function t(e) { var r, s, i, a = e.length,
                        n = !1; for (r = a - 1; r >= 0; r -= 1)
                        if ("sh" == e[r].ty) { if (e[r].ks.k.i) e[r].ks.k.c = e[r].closed;
                            else
                                for (i = e[r].ks.k.length, s = 0; i > s; s += 1) e[r].ks.k[s].s && (e[r].ks.k[s].s[0].c = e[r].closed), e[r].ks.k[s].e && (e[r].ks.k[s].e[0].c = e[r].closed);
                            n = !0 } else "gr" == e[r].ty && t(e[r].it) }

                function e(e) { var r, s, i, a, n, o, h = e.length; for (s = 0; h > s; s += 1) { if (r = e[s], r.hasMask) { var l = r.masksProperties; for (a = l.length, i = 0; a > i; i += 1)
                                if (l[i].pt.k.i) l[i].pt.k.c = l[i].cl;
                                else
                                    for (o = l[i].pt.k.length, n = 0; o > n; n += 1) l[i].pt.k[n].s && (l[i].pt.k[n].s[0].c = l[i].cl), l[i].pt.k[n].e && (l[i].pt.k[n].e[0].c = l[i].cl) }
                        4 === r.ty && t(r.shapes) } } var r = [4, 4, 18]; return function(t) { if (i(r, t.v) && (e(t.layers), t.assets)) { var s, a = t.assets.length; for (s = 0; a > s; s += 1) t.assets[s].layers && e(t.assets[s].layers) } } }(),
            p = {}; return p.completeData = a, p }

    function ShapePath() { this.c = !1, this._length = 0, this._maxLength = 8, this.v = Array.apply(null, { length: this._maxLength }), this.o = Array.apply(null, { length: this._maxLength }), this.i = Array.apply(null, { length: this._maxLength }) }

    function ShapeModifier() {}

    function TrimModifier() {}

    function RoundCornersModifier() {}

    function RepeaterModifier() {}

    function ShapeCollection() { this._length = 0, this._maxLength = 4, this.shapes = Array.apply(null, { length: this._maxLength }) }

    function BaseRenderer() {}

    function SVGRenderer(t, e) { this.animationItem = t, this.layers = null, this.renderedFrame = -1, this.globalData = { frameNum: -1 }, this.renderConfig = { preserveAspectRatio: e && e.preserveAspectRatio || "xMidYMid meet", progressiveLoad: e && e.progressiveLoad || !1 }, this.elements = [], this.pendingElements = [], this.destroyed = !1 }

    function MaskElement(t, e, r) { this.dynamicProperties = [], this.data = t, this.element = e, this.globalData = r, this.paths = [], this.storedData = [], this.masksProperties = this.data.masksProperties, this.viewData = new Array(this.masksProperties.length), this.maskElement = null, this.firstFrame = !0; var s, i, a, n, o, h, l, p, m = this.globalData.defs,
            f = this.masksProperties.length,
            c = this.masksProperties,
            d = 0,
            u = [],
            y = randomString(10),
            g = "clipPath",
            v = "clip-path"; for (s = 0; f > s; s++)
            if (("a" !== c[s].mode && "n" !== c[s].mode || c[s].inv || 100 !== c[s].o.k) && (g = "mask", v = "mask"), "s" != c[s].mode && "i" != c[s].mode || 0 != d ? o = null : (o = document.createElementNS(svgNS, "rect"), o.setAttribute("fill", "#ffffff"), o.setAttribute("width", this.element.comp.data.w), o.setAttribute("height", this.element.comp.data.h), u.push(o)), i = document.createElementNS(svgNS, "path"), "n" != c[s].mode) { if (d += 1, "s" == c[s].mode ? i.setAttribute("fill", "#000000") : i.setAttribute("fill", "#ffffff"), i.setAttribute("clip-rule", "nonzero"), 0 !== c[s].x.k) { g = "mask", v = "mask", p = PropertyFactory.getProp(this.element, c[s].x, 0, null, this.dynamicProperties); var b = "fi_" + randomString(10);
                    h = document.createElementNS(svgNS, "filter"), h.setAttribute("id", b), l = document.createElementNS(svgNS, "feMorphology"), l.setAttribute("operator", "dilate"), l.setAttribute("in", "SourceGraphic"), l.setAttribute("radius", "0"), h.appendChild(l), m.appendChild(h), "s" == c[s].mode ? i.setAttribute("stroke", "#000000") : i.setAttribute("stroke", "#ffffff") } else l = null, p = null; if (this.storedData[s] = { elem: i, x: p, expan: l, lastPath: "", lastOperator: "", filterId: b, lastRadius: 0 }, "i" == c[s].mode) { n = u.length; var E = document.createElementNS(svgNS, "g"); for (a = 0; n > a; a += 1) E.appendChild(u[a]); var P = document.createElementNS(svgNS, "mask");
                    P.setAttribute("mask-type", "alpha"), P.setAttribute("id", y + "_" + d), P.appendChild(i), m.appendChild(P), E.setAttribute("mask", "url(#" + y + "_" + d + ")"), u.length = 0, u.push(E) } else u.push(i);
                c[s].inv && !this.solidPath && (this.solidPath = this.createLayerSolidPath()), this.viewData[s] = { elem: i, lastPath: "", op: PropertyFactory.getProp(this.element, c[s].o, 0, .01, this.dynamicProperties), prop: ShapePropertyFactory.getShapeProp(this.element, c[s], 3, this.dynamicProperties, null) }, o && (this.viewData[s].invRect = o), this.viewData[s].prop.k || this.drawPath(c[s], this.viewData[s].prop.v, this.viewData[s]) } else this.viewData[s] = { op: PropertyFactory.getProp(this.element, c[s].o, 0, .01, this.dynamicProperties), prop: ShapePropertyFactory.getShapeProp(this.element, c[s], 3, this.dynamicProperties, null), elem: i }, m.appendChild(i);
        for (this.maskElement = document.createElementNS(svgNS, g), f = u.length, s = 0; f > s; s += 1) this.maskElement.appendChild(u[s]);
        this.maskElement.setAttribute("id", y), d > 0 && this.element.maskedElement.setAttribute(v, "url(#" + y + ")"), m.appendChild(this.maskElement) }

    function BaseElement() {}

    function SVGBaseElement(t, e, r, s, i) { this.globalData = r, this.comp = s, this.data = t, this.matteElement = null, this.transformedElement = null, this.parentContainer = e, this.layerId = i ? i.layerId : "ly_" + randomString(10), this.placeholder = i, this.init() }

    function ITextElement(t, e, r, s) {}

    function SVGTextElement(t, e, r, s, i) { this.textSpans = [], this.renderType = "svg", this._parent.constructor.call(this, t, e, r, s, i) }

    function SVGTintFilter(t, e) { this.filterManager = e; var r = document.createElementNS(svgNS, "feColorMatrix"); if (r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "linearRGB"), r.setAttribute("values", "0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0"), r.setAttribute("result", "f1"), t.appendChild(r), r = document.createElementNS(svgNS, "feColorMatrix"), r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "sRGB"), r.setAttribute("values", "1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0"), r.setAttribute("result", "f2"), t.appendChild(r), this.matrixFilter = r, 100 !== e.effectElements[2].p.v || e.effectElements[2].p.k) { var s = document.createElementNS(svgNS, "feMerge");
            t.appendChild(s); var i;
            i = document.createElementNS(svgNS, "feMergeNode"), i.setAttribute("in", "SourceGraphic"), s.appendChild(i), i = document.createElementNS(svgNS, "feMergeNode"), i.setAttribute("in", "f2"), s.appendChild(i) } }

    function SVGFillFilter(t, e) { this.filterManager = e; var r = document.createElementNS(svgNS, "feColorMatrix");
        r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "sRGB"), r.setAttribute("values", "1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0"), t.appendChild(r), this.matrixFilter = r }

    function SVGStrokeEffect(t, e) { this.initialized = !1, this.filterManager = e, this.elem = t, this.paths = [] }

    function SVGTritoneFilter(t, e) { this.filterManager = e; var r = document.createElementNS(svgNS, "feColorMatrix");
        r.setAttribute("type", "matrix"), r.setAttribute("color-interpolation-filters", "linearRGB"), r.setAttribute("values", "0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0"), r.setAttribute("result", "f1"), t.appendChild(r); var s = document.createElementNS(svgNS, "feComponentTransfer");
        s.setAttribute("color-interpolation-filters", "sRGB"), t.appendChild(s), this.matrixFilter = s; var i = document.createElementNS(svgNS, "feFuncR");
        i.setAttribute("type", "table"), s.appendChild(i), this.feFuncR = i; var a = document.createElementNS(svgNS, "feFuncG");
        a.setAttribute("type", "table"), s.appendChild(a), this.feFuncG = a; var n = document.createElementNS(svgNS, "feFuncB");
        n.setAttribute("type", "table"), s.appendChild(n), this.feFuncB = n }

    function SVGProLevelsFilter(t, e) { this.filterManager = e; var r = this.filterManager.effectElements,
            s = document.createElementNS(svgNS, "feComponentTransfer");
        (r[9].p.k || 0 !== r[9].p.v || r[10].p.k || 1 !== r[10].p.v || r[11].p.k || 1 !== r[11].p.v || r[12].p.k || 0 !== r[12].p.v || r[13].p.k || 1 !== r[13].p.v) && (this.feFuncR = this.createFeFunc("feFuncR", s)), (r[16].p.k || 0 !== r[16].p.v || r[17].p.k || 1 !== r[17].p.v || r[18].p.k || 1 !== r[18].p.v || r[19].p.k || 0 !== r[19].p.v || r[20].p.k || 1 !== r[20].p.v) && (this.feFuncG = this.createFeFunc("feFuncG", s)), (r[23].p.k || 0 !== r[23].p.v || r[24].p.k || 1 !== r[24].p.v || r[25].p.k || 1 !== r[25].p.v || r[26].p.k || 0 !== r[26].p.v || r[27].p.k || 1 !== r[27].p.v) && (this.feFuncB = this.createFeFunc("feFuncB", s)), (r[30].p.k || 0 !== r[30].p.v || r[31].p.k || 1 !== r[31].p.v || r[32].p.k || 1 !== r[32].p.v || r[33].p.k || 0 !== r[33].p.v || r[34].p.k || 1 !== r[34].p.v) && (this.feFuncA = this.createFeFunc("feFuncA", s)), (this.feFuncR || this.feFuncG || this.feFuncB || this.feFuncA) && (s.setAttribute("color-interpolation-filters", "sRGB"), t.appendChild(s), s = document.createElementNS(svgNS, "feComponentTransfer")), (r[2].p.k || 0 !== r[2].p.v || r[3].p.k || 1 !== r[3].p.v || r[4].p.k || 1 !== r[4].p.v || r[5].p.k || 0 !== r[5].p.v || r[6].p.k || 1 !== r[6].p.v) && (s.setAttribute("color-interpolation-filters", "sRGB"), t.appendChild(s), this.feFuncRComposed = this.createFeFunc("feFuncR", s), this.feFuncGComposed = this.createFeFunc("feFuncG", s), this.feFuncBComposed = this.createFeFunc("feFuncB", s)) }

    function SVGDropShadowEffect(t, e) { t.setAttribute("x", "-100%"), t.setAttribute("y", "-100%"), t.setAttribute("width", "400%"), t.setAttribute("height", "400%"), this.filterManager = e; var r = document.createElementNS(svgNS, "feGaussianBlur");
        r.setAttribute("in", "SourceAlpha"), r.setAttribute("result", "drop_shadow_1"), r.setAttribute("stdDeviation", "0"), this.feGaussianBlur = r, t.appendChild(r); var s = document.createElementNS(svgNS, "feOffset");
        s.setAttribute("dx", "25"), s.setAttribute("dy", "0"), s.setAttribute("in", "drop_shadow_1"), s.setAttribute("result", "drop_shadow_2"), this.feOffset = s, t.appendChild(s); var i = document.createElementNS(svgNS, "feFlood");
        i.setAttribute("flood-color", "#00ff00"), i.setAttribute("flood-opacity", "1"), i.setAttribute("result", "drop_shadow_3"), this.feFlood = i, t.appendChild(i); var a = document.createElementNS(svgNS, "feComposite");
        a.setAttribute("in", "drop_shadow_3"), a.setAttribute("in2", "drop_shadow_2"), a.setAttribute("operator", "in"), a.setAttribute("result", "drop_shadow_4"), t.appendChild(a); var n = document.createElementNS(svgNS, "feMerge");
        t.appendChild(n); var o;
        o = document.createElementNS(svgNS, "feMergeNode"), n.appendChild(o), o = document.createElementNS(svgNS, "feMergeNode"), o.setAttribute("in", "SourceGraphic"), this.feMergeNode = o, this.feMerge = n, this.originalNodeAdded = !1, n.appendChild(o) }

    function SVGEffects(t) { var e, r = t.data.ef.length,
            s = randomString(10),
            i = filtersFactory.createFilter(s),
            a = 0;
        this.filters = []; var n; for (e = 0; r > e; e += 1) 20 === t.data.ef[e].ty ? (a += 1, n = new SVGTintFilter(i, t.effects.effectElements[e]), this.filters.push(n)) : 21 === t.data.ef[e].ty ? (a += 1, n = new SVGFillFilter(i, t.effects.effectElements[e]), this.filters.push(n)) : 22 === t.data.ef[e].ty ? (n = new SVGStrokeEffect(t, t.effects.effectElements[e]), this.filters.push(n)) : 23 === t.data.ef[e].ty ? (a += 1, n = new SVGTritoneFilter(i, t.effects.effectElements[e]), this.filters.push(n)) : 24 === t.data.ef[e].ty ? (a += 1, n = new SVGProLevelsFilter(i, t.effects.effectElements[e]), this.filters.push(n)) : 25 === t.data.ef[e].ty && (a += 1, n = new SVGDropShadowEffect(i, t.effects.effectElements[e]), this.filters.push(n));
        a && (t.globalData.defs.appendChild(i), t.layerElement.setAttribute("filter", "url(#" + s + ")")) }

    function ICompElement(t, e, r, s, i) { this._parent.constructor.call(this, t, e, r, s, i), this.layers = t.layers, this.supports3d = !0, this.completeLayers = !1, this.pendingElements = [], this.elements = this.layers ? Array.apply(null, { length: this.layers.length }) : [], this.data.tm && (this.tm = PropertyFactory.getProp(this, this.data.tm, 0, r.frameRate, this.dynamicProperties)), this.data.xt ? (this.layerElement = document.createElementNS(svgNS, "g"), this.buildAllItems()) : r.progressiveLoad || this.buildAllItems() }

    function IImageElement(t, e, r, s, i) { this.assetData = r.getAssetData(t.refId), this._parent.constructor.call(this, t, e, r, s, i) }

    function IShapeElement(t, e, r, s, i) { this.shapes = [], this.shapesData = t.shapes, this.stylesList = [], this.viewData = [], this.shapeModifiers = [], this._parent.constructor.call(this, t, e, r, s, i) }

    function ISolidElement(t, e, r, s, i) { this._parent.constructor.call(this, t, e, r, s, i) }

    function CanvasRenderer(t, e) { this.animationItem = t, this.renderConfig = { clearCanvas: e && void 0 !== e.clearCanvas ? e.clearCanvas : !0, context: e && e.context || null, progressiveLoad: e && e.progressiveLoad || !1, preserveAspectRatio: e && e.preserveAspectRatio || "xMidYMid meet" }, this.renderConfig.dpr = e && e.dpr || 1, this.animationItem.wrapper && (this.renderConfig.dpr = e && e.dpr || window.devicePixelRatio || 1), this.renderedFrame = -1, this.globalData = { frameNum: -1 }, this.contextData = { saved: Array.apply(null, { length: 15 }), savedOp: Array.apply(null, { length: 15 }), cArrPos: 0, cTr: new Matrix, cO: 1 }; var r, s = 15; for (r = 0; s > r; r += 1) this.contextData.saved[r] = Array.apply(null, { length: 16 });
        this.elements = [], this.pendingElements = [], this.transformMat = new Matrix, this.completeLayers = !1 }

    function HybridRenderer(t) { this.animationItem = t, this.layers = null, this.renderedFrame = -1, this.globalData = { frameNum: -1 }, this.pendingElements = [], this.elements = [], this.threeDElements = [], this.destroyed = !1, this.camera = null, this.supports3d = !0 }

    function CVBaseElement(t, e, r) { this.globalData = r, this.data = t, this.comp = e, this.canvasContext = r.canvasContext, this.init() }

    function CVCompElement(t, e, r) { this._parent.constructor.call(this, t, e, r); var s = {}; for (var i in r) r.hasOwnProperty(i) && (s[i] = r[i]);
        s.renderer = this, s.compHeight = this.data.h, s.compWidth = this.data.w, this.renderConfig = { clearCanvas: !0 }, this.contextData = { saved: Array.apply(null, { length: 15 }), savedOp: Array.apply(null, { length: 15 }), cArrPos: 0, cTr: new Matrix, cO: 1 }, this.completeLayers = !1; var a, n = 15; for (a = 0; n > a; a += 1) this.contextData.saved[a] = Array.apply(null, { length: 16 });
        this.transformMat = new Matrix, this.parentGlobalData = this.globalData; var o = document.createElement("canvas");
        s.canvasContext = o.getContext("2d"), this.canvasContext = s.canvasContext, o.width = this.data.w, o.height = this.data.h, this.canvas = o, this.globalData = s, this.layers = t.layers, this.pendingElements = [], this.elements = Array.apply(null, { length: this.layers.length }), this.data.tm && (this.tm = PropertyFactory.getProp(this, this.data.tm, 0, r.frameRate, this.dynamicProperties)), (this.data.xt || !r.progressiveLoad) && this.buildAllItems() }

    function CVImageElement(t, e, r) { this.assetData = r.getAssetData(t.refId), this._parent.constructor.call(this, t, e, r), this.globalData.addPendingElement() }

    function CVMaskElement(t, e) { this.data = t, this.element = e, this.dynamicProperties = [], this.masksProperties = this.data.masksProperties, this.viewData = new Array(this.masksProperties.length); var r, s = this.masksProperties.length; for (r = 0; s > r; r++) this.viewData[r] = ShapePropertyFactory.getShapeProp(this.element, this.masksProperties[r], 3, this.dynamicProperties, null) }

    function CVShapeElement(t, e, r) { this.shapes = [], this.stylesList = [], this.viewData = [], this.shapeModifiers = [], this.shapesData = t.shapes, this.firstFrame = !0, this._parent.constructor.call(this, t, e, r) }

    function CVSolidElement(t, e, r) { this._parent.constructor.call(this, t, e, r) }

    function CVTextElement(t, e, r) { this.textSpans = [], this.yOffset = 0, this.fillColorAnim = !1, this.strokeColorAnim = !1, this.strokeWidthAnim = !1, this.stroke = !1, this.fill = !1, this.justifyOffset = 0, this.currentRender = null, this.renderType = "canvas", this.values = { fill: "rgba(0,0,0,0)", stroke: "rgba(0,0,0,0)", sWidth: 0, fValue: "" }, this._parent.constructor.call(this, t, e, r) }

    function HBaseElement(t, e, r, s, i) { this.globalData = r, this.comp = s, this.data = t, this.matteElement = null, this.parentContainer = e, this.layerId = i ? i.layerId : "ly_" + randomString(10), this.placeholder = i, this.init() }

    function HSolidElement(t, e, r, s, i) { this._parent.constructor.call(this, t, e, r, s, i) }

    function HCompElement(t, e, r, s, i) { this._parent.constructor.call(this, t, e, r, s, i), this.layers = t.layers, this.supports3d = !0, this.completeLayers = !1, this.pendingElements = [], this.elements = Array.apply(null, { length: this.layers.length }), this.data.tm && (this.tm = PropertyFactory.getProp(this, this.data.tm, 0, r.frameRate, this.dynamicProperties)), this.data.hasMask && (this.supports3d = !1), this.data.xt && (this.layerElement = document.createElement("div")), this.buildAllItems() }

    function HShapeElement(t, e, r, s, i) { this.shapes = [], this.shapeModifiers = [], this.shapesData = t.shapes, this.stylesList = [], this.viewData = [], this._parent.constructor.call(this, t, e, r, s, i), this.addedTransforms = { mdf: !1, mats: [this.finalTransform.mat] }, this.currentBBox = { x: 999999, y: -999999, h: 0, w: 0 } }

    function HTextElement(t, e, r, s, i) { this.textSpans = [], this.textPaths = [], this.currentBBox = { x: 999999, y: -999999, h: 0, w: 0 }, this.renderType = "svg", this.isMasked = !1, this._parent.constructor.call(this, t, e, r, s, i) }

    function HImageElement(t, e, r, s, i) { this.assetData = r.getAssetData(t.refId), this._parent.constructor.call(this, t, e, r, s, i) }

    function HCameraElement(t, e, r, s, i) { if (this._parent.constructor.call(this, t, e, r, s, i), this.pe = PropertyFactory.getProp(this, t.pe, 0, 0, this.dynamicProperties), t.ks.p.s ? (this.px = PropertyFactory.getProp(this, t.ks.p.x, 1, 0, this.dynamicProperties), this.py = PropertyFactory.getProp(this, t.ks.p.y, 1, 0, this.dynamicProperties), this.pz = PropertyFactory.getProp(this, t.ks.p.z, 1, 0, this.dynamicProperties)) : this.p = PropertyFactory.getProp(this, t.ks.p, 1, 0, this.dynamicProperties), t.ks.a && (this.a = PropertyFactory.getProp(this, t.ks.a, 1, 0, this.dynamicProperties)), t.ks.or.k.length) { var a, n = t.ks.or.k.length; for (a = 0; n > a; a += 1) t.ks.or.k[a].to = null, t.ks.or.k[a].ti = null }
        this.or = PropertyFactory.getProp(this, t.ks.or, 1, degToRads, this.dynamicProperties), this.or.sh = !0, this.rx = PropertyFactory.getProp(this, t.ks.rx, 0, degToRads, this.dynamicProperties), this.ry = PropertyFactory.getProp(this, t.ks.ry, 0, degToRads, this.dynamicProperties), this.rz = PropertyFactory.getProp(this, t.ks.rz, 0, degToRads, this.dynamicProperties), this.mat = new Matrix }

    function SliderEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 0, 0, r) }

    function AngleEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 0, 0, r) }

    function ColorEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 1, 0, r) }

    function PointEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 1, 0, r) }

    function LayerIndexEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 0, 0, r) }

    function MaskIndexEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 0, 0, r) }

    function CheckboxEffect(t, e, r) { this.p = PropertyFactory.getProp(e, t.v, 0, 0, r) }

    function NoValueEffect() { this.p = {} }

    function EffectsManager(t, e, r) { var s = t.ef;
        this.effectElements = []; var i, a, n = s.length; for (i = 0; n > i; i++) a = new GroupEffect(s[i], e, r), this.effectElements.push(a) }

    function GroupEffect(t, e, r) { this.dynamicProperties = [], this.init(t, e, this.dynamicProperties), this.dynamicProperties.length && r.push(this) }

    function play(t) { animationManager.play(t) }

    function pause(t) { animationManager.pause(t) }

    function togglePause(t) { animationManager.togglePause(t) }

    function setSpeed(t, e) { animationManager.setSpeed(t, e) }

    function setDirection(t, e) { animationManager.setDirection(t, e) }

    function stop(t) { animationManager.stop(t) }

    function moveFrame(t) { animationManager.moveFrame(t) }

    function searchAnimations() { standalone === !0 ? animationManager.searchAnimations(animationData, standalone, renderer) : animationManager.searchAnimations() }

    function registerAnimation(t) { return animationManager.registerAnimation(t) }

    function resize() { animationManager.resize() }

    function start() { animationManager.start() }

    function goToAndStop(t, e, r) { animationManager.goToAndStop(t, e, r) }

    function setSubframeRendering(t) { subframeEnabled = t }

    function loadAnimation(t) { return standalone === !0 && (t.animationData = JSON.parse(animationData)), animationManager.loadAnimation(t) }

    function destroy(t) { return animationManager.destroy(t) }

    function setQuality(t) { if ("string" == typeof t) switch (t) {
            case "high":
                defaultCurveSegments = 200; break;
            case "medium":
                defaultCurveSegments = 50; break;
            case "low":
                defaultCurveSegments = 10 } else !isNaN(t) && t > 1 && (defaultCurveSegments = t);
        roundValues(defaultCurveSegments >= 50 ? !1 : !0) }

    function installPlugin(t, e) { "expressions" === t && (expressionsPlugin = e) }

    function getFactory(t) { switch (t) {
            case "propertyFactory":
                return PropertyFactory;
            case "shapePropertyFactory":
                return ShapePropertyFactory;
            case "matrix":
                return Matrix } }

    function checkReady() { "complete" === document.readyState && (clearInterval(readyStateCheckInterval), searchAnimations()) }

    function getQueryVariable(t) { for (var e = queryString.split("&"), r = 0; r < e.length; r++) { var s = e[r].split("="); if (decodeURIComponent(s[0]) == t) return decodeURIComponent(s[1]) } }
    var svgNS = "http://www.w3.org/2000/svg",
        subframeEnabled = !0,
        expressionsPlugin, isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent),
        cachedColors = {},
        bm_rounder = Math.round,
        bm_rnd, bm_pow = Math.pow,
        bm_sqrt = Math.sqrt,
        bm_abs = Math.abs,
        bm_floor = Math.floor,
        bm_max = Math.max,
        bm_min = Math.min,
        blitter = 10,
        BMMath = {};
    ! function() { var t, e = Object.getOwnPropertyNames(Math),
            r = e.length; for (t = 0; r > t; t += 1) BMMath[e[t]] = Math[e[t]] }(), BMMath.random = Math.random, BMMath.abs = function(t) { var e = typeof t; if ("object" === e && t.length) { var r, s = Array.apply(null, { length: t.length }),
                i = t.length; for (r = 0; i > r; r += 1) s[r] = Math.abs(t[r]); return s } return Math.abs(t) };
    var defaultCurveSegments = 150,
        degToRads = Math.PI / 180,
        roundCorner = .5519;
    roundValues(!1);
    var rgbToHex = function() { var t, e, r = []; for (t = 0; 256 > t; t += 1) e = t.toString(16), r[t] = 1 == e.length ? "0" + e : e; return function(t, e, s) { return 0 > t && (t = 0), 0 > e && (e = 0), 0 > s && (s = 0), "#" + r[t] + r[e] + r[s] } }(),
        fillColorToString = function() { var t = []; return function(e, r) { return void 0 !== r && (e[3] = r), t[e[0]] || (t[e[0]] = {}), t[e[0]][e[1]] || (t[e[0]][e[1]] = {}), t[e[0]][e[1]][e[2]] || (t[e[0]][e[1]][e[2]] = {}), t[e[0]][e[1]][e[2]][e[3]] || (t[e[0]][e[1]][e[2]][e[3]] = "rgba(" + e.join(",") + ")"), t[e[0]][e[1]][e[2]][e[3]] } }(),
        Matrix = function() {
            function t() {
                return this.props[0] = 1, this.props[1] = 0, this.props[2] = 0, this.props[3] = 0,
                    this.props[4] = 0, this.props[5] = 1, this.props[6] = 0, this.props[7] = 0, this.props[8] = 0, this.props[9] = 0, this.props[10] = 1, this.props[11] = 0, this.props[12] = 0, this.props[13] = 0, this.props[14] = 0, this.props[15] = 1, this
            }

            function e(t) { if (0 === t) return this; var e = Math.cos(t),
                    r = Math.sin(t); return this._t(e, -r, 0, 0, r, e, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1) }

            function r(t) { if (0 === t) return this; var e = Math.cos(t),
                    r = Math.sin(t); return this._t(1, 0, 0, 0, 0, e, -r, 0, 0, r, e, 0, 0, 0, 0, 1) }

            function s(t) { if (0 === t) return this; var e = Math.cos(t),
                    r = Math.sin(t); return this._t(e, 0, r, 0, 0, 1, 0, 0, -r, 0, e, 0, 0, 0, 0, 1) }

            function i(t) { if (0 === t) return this; var e = Math.cos(t),
                    r = Math.sin(t); return this._t(e, -r, 0, 0, r, e, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1) }

            function a(t, e) { return this._t(1, e, t, 1, 0, 0) }

            function n(t, e) { return this.shear(Math.tan(t), Math.tan(e)) }

            function o(t, e) { var r = Math.cos(e),
                    s = Math.sin(e); return this._t(r, s, 0, 0, -s, r, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)._t(1, 0, 0, 0, Math.tan(t), 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)._t(r, -s, 0, 0, s, r, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1) }

            function h(t, e, r) { return r = isNaN(r) ? 1 : r, 1 == t && 1 == e && 1 == r ? this : this._t(t, 0, 0, 0, 0, e, 0, 0, 0, 0, r, 0, 0, 0, 0, 1) }

            function l(t, e, r, s, i, a, n, o, h, l, p, m, f, c, d, u) { return this.props[0] = t, this.props[1] = e, this.props[2] = r, this.props[3] = s, this.props[4] = i, this.props[5] = a, this.props[6] = n, this.props[7] = o, this.props[8] = h, this.props[9] = l, this.props[10] = p, this.props[11] = m, this.props[12] = f, this.props[13] = c, this.props[14] = d, this.props[15] = u, this }

            function p(t, e, r) { return r = r || 0, 0 !== t || 0 !== e || 0 !== r ? this._t(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, t, e, r, 1) : this }

            function m(t, e, r, s, i, a, n, o, h, l, p, m, f, c, d, u) { if (1 === t && 0 === e && 0 === r && 0 === s && 0 === i && 1 === a && 0 === n && 0 === o && 0 === h && 0 === l && 1 === p && 0 === m) return (0 !== f || 0 !== c || 0 !== d) && (this.props[12] = this.props[12] * t + this.props[13] * i + this.props[14] * h + this.props[15] * f, this.props[13] = this.props[12] * e + this.props[13] * a + this.props[14] * l + this.props[15] * c, this.props[14] = this.props[12] * r + this.props[13] * n + this.props[14] * p + this.props[15] * d, this.props[15] = this.props[12] * s + this.props[13] * o + this.props[14] * m + this.props[15] * u), this; var y = this.props[0],
                    g = this.props[1],
                    v = this.props[2],
                    b = this.props[3],
                    E = this.props[4],
                    P = this.props[5],
                    x = this.props[6],
                    S = this.props[7],
                    C = this.props[8],
                    k = this.props[9],
                    M = this.props[10],
                    A = this.props[11],
                    D = this.props[12],
                    T = this.props[13],
                    w = this.props[14],
                    F = this.props[15]; return this.props[0] = y * t + g * i + v * h + b * f, this.props[1] = y * e + g * a + v * l + b * c, this.props[2] = y * r + g * n + v * p + b * d, this.props[3] = y * s + g * o + v * m + b * u, this.props[4] = E * t + P * i + x * h + S * f, this.props[5] = E * e + P * a + x * l + S * c, this.props[6] = E * r + P * n + x * p + S * d, this.props[7] = E * s + P * o + x * m + S * u, this.props[8] = C * t + k * i + M * h + A * f, this.props[9] = C * e + k * a + M * l + A * c, this.props[10] = C * r + k * n + M * p + A * d, this.props[11] = C * s + k * o + M * m + A * u, this.props[12] = D * t + T * i + w * h + F * f, this.props[13] = D * e + T * a + w * l + F * c, this.props[14] = D * r + T * n + w * p + F * d, this.props[15] = D * s + T * o + w * m + F * u, this }

            function f(t) { var e; for (e = 0; 16 > e; e += 1) t.props[e] = this.props[e] }

            function c(t) { var e; for (e = 0; 16 > e; e += 1) this.props[e] = t[e] }

            function d(t, e, r) { return { x: t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12], y: t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13], z: t * this.props[2] + e * this.props[6] + r * this.props[10] + this.props[14] } }

            function u(t, e, r) { return t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12] }

            function y(t, e, r) { return t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13] }

            function g(t, e, r) { return t * this.props[2] + e * this.props[6] + r * this.props[10] + this.props[14] }

            function v(t) { var e, r = this.props[0] * this.props[5] - this.props[1] * this.props[4],
                    s = this.props[5] / r,
                    i = -this.props[1] / r,
                    a = -this.props[4] / r,
                    n = this.props[0] / r,
                    o = (this.props[4] * this.props[13] - this.props[5] * this.props[12]) / r,
                    h = -(this.props[0] * this.props[13] - this.props[1] * this.props[12]) / r,
                    l = t.length,
                    p = []; for (e = 0; l > e; e += 1) p[e] = [t[e][0] * s + t[e][1] * a + o, t[e][0] * i + t[e][1] * n + h, 0]; return p }

            function b(t, e, r, s) { if (s && 2 === s) { var i = point_pool.newPoint(); return i[0] = t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12], i[1] = t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13], i } return [t * this.props[0] + e * this.props[4] + r * this.props[8] + this.props[12], t * this.props[1] + e * this.props[5] + r * this.props[9] + this.props[13], t * this.props[2] + e * this.props[6] + r * this.props[10] + this.props[14]] }

            function E(t, e) { return bm_rnd(t * this.props[0] + e * this.props[4] + this.props[12]) + "," + bm_rnd(t * this.props[1] + e * this.props[5] + this.props[13]) }

            function P() { return [this.props[0], this.props[1], this.props[2], this.props[3], this.props[4], this.props[5], this.props[6], this.props[7], this.props[8], this.props[9], this.props[10], this.props[11], this.props[12], this.props[13], this.props[14], this.props[15]] }

            function x() { return isSafari ? "matrix3d(" + roundTo2Decimals(this.props[0]) + "," + roundTo2Decimals(this.props[1]) + "," + roundTo2Decimals(this.props[2]) + "," + roundTo2Decimals(this.props[3]) + "," + roundTo2Decimals(this.props[4]) + "," + roundTo2Decimals(this.props[5]) + "," + roundTo2Decimals(this.props[6]) + "," + roundTo2Decimals(this.props[7]) + "," + roundTo2Decimals(this.props[8]) + "," + roundTo2Decimals(this.props[9]) + "," + roundTo2Decimals(this.props[10]) + "," + roundTo2Decimals(this.props[11]) + "," + roundTo2Decimals(this.props[12]) + "," + roundTo2Decimals(this.props[13]) + "," + roundTo2Decimals(this.props[14]) + "," + roundTo2Decimals(this.props[15]) + ")" : (this.cssParts[1] = this.props.join(","), this.cssParts.join("")) }

            function S() { return "matrix(" + this.props[0] + "," + this.props[1] + "," + this.props[4] + "," + this.props[5] + "," + this.props[12] + "," + this.props[13] + ")" }

            function C() { return "" + this.toArray() }
            return function() { this.reset = t, this.rotate = e, this.rotateX = r, this.rotateY = s, this.rotateZ = i, this.skew = n, this.skewFromAxis = o, this.shear = a, this.scale = h, this.setTransform = l, this.translate = p, this.transform = m, this.applyToPoint = d, this.applyToX = u, this.applyToY = y, this.applyToZ = g, this.applyToPointArray = b, this.applyToPointStringified = E, this.toArray = P, this.toCSS = x, this.to2dCSS = S, this.toString = C, this.clone = f, this.cloneFromProps = c, this.inversePoints = v, this._t = this.transform, this.props = [1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1], this.cssParts = ["matrix3d(", "", ")"] }
        }();
    ! function(t, e) {
        function r(r, l, p) { var c = [];
            l = 1 == l ? { entropy: !0 } : l || {}; var v = n(a(l.entropy ? [r, h(t)] : null == r ? o() : r, 3), c),
                b = new s(c),
                E = function() { for (var t = b.g(f), e = u, r = 0; y > t;) t = (t + r) * m, e *= m, r = b.g(1); for (; t >= g;) t /= 2, e /= 2, r >>>= 1; return (t + r) / e }; return E.int32 = function() { return 0 | b.g(4) }, E.quick = function() { return b.g(4) / 4294967296 }, E["double"] = E, n(h(b.S), t), (l.pass || p || function(t, r, s, a) { return a && (a.S && i(a, b), t.state = function() { return i(b, {}) }), s ? (e[d] = t, r) : t })(E, v, "global" in l ? l.global : this == e, l.state) }

        function s(t) { var e, r = t.length,
                s = this,
                i = 0,
                a = s.i = s.j = 0,
                n = s.S = []; for (r || (t = [r++]); m > i;) n[i] = i++; for (i = 0; m > i; i++) n[i] = n[a = v & a + t[i % r] + (e = n[i])], n[a] = e;
            (s.g = function(t) { for (var e, r = 0, i = s.i, a = s.j, n = s.S; t--;) e = n[i = v & i + 1], r = r * m + n[v & (n[i] = n[a = v & a + e]) + (n[a] = e)]; return s.i = i, s.j = a, r })(m) }

        function i(t, e) { return e.i = t.i, e.j = t.j, e.S = t.S.slice(), e }

        function a(t, e) { var r, s = [],
                i = typeof t; if (e && "object" == i)
                for (r in t) try { s.push(a(t[r], e - 1)) } catch (n) {}
            return s.length ? s : "string" == i ? t : t + "\x00" }

        function n(t, e) { for (var r, s = t + "", i = 0; i < s.length;) e[v & i] = v & (r ^= 19 * e[v & i]) + s.charCodeAt(i++); return h(e) }

        function o() { try { if (l) return h(l.randomBytes(m)); var e = new Uint8Array(m); return (p.crypto || p.msCrypto).getRandomValues(e), h(e) } catch (r) { var s = p.navigator,
                    i = s && s.plugins; return [+new Date, p, i, p.screen, h(t)] } }

        function h(t) { return String.fromCharCode.apply(0, t) } var l, p = this,
            m = 256,
            f = 6,
            c = 52,
            d = "random",
            u = e.pow(m, f),
            y = e.pow(2, c),
            g = 2 * y,
            v = m - 1;
        e["seed" + d] = r, n(e.random(), t) }([], BMMath);
    var BezierFactory = function() {
            function t(t, e, r, s, i) { var a = i || ("bez_" + t + "_" + e + "_" + r + "_" + s).replace(/\./g, "p"); if (p[a]) return p[a]; var n = new h([t, e, r, s]); return p[a] = n, n }

            function e(t, e) { return 1 - 3 * e + 3 * t }

            function r(t, e) { return 3 * e - 6 * t }

            function s(t) { return 3 * t }

            function i(t, i, a) { return ((e(i, a) * t + r(i, a)) * t + s(i)) * t }

            function a(t, i, a) { return 3 * e(i, a) * t * t + 2 * r(i, a) * t + s(i) }

            function n(t, e, r, s, a) { var n, o, h = 0;
                do o = e + (r - e) / 2, n = i(o, s, a) - t, n > 0 ? r = o : e = o; while (Math.abs(n) > c && ++h < d); return o }

            function o(t, e, r, s) { for (var n = 0; m > n; ++n) { var o = a(e, r, s); if (0 === o) return e; var h = i(e, r, s) - t;
                    e -= h / o } return e }

            function h(t) { this._p = t, this._mSampleValues = g ? new Float32Array(u) : new Array(u), this._precomputed = !1, this.get = this.get.bind(this) } var l = {};
            l.getBezierEasing = t; var p = {},
                m = 4,
                f = .001,
                c = 1e-7,
                d = 10,
                u = 11,
                y = 1 / (u - 1),
                g = "function" == typeof Float32Array; return h.prototype = { get: function(t) { var e = this._p[0],
                        r = this._p[1],
                        s = this._p[2],
                        a = this._p[3]; return this._precomputed || this._precompute(), e === r && s === a ? t : 0 === t ? 0 : 1 === t ? 1 : i(this._getTForX(t), r, a) }, _precompute: function() { var t = this._p[0],
                        e = this._p[1],
                        r = this._p[2],
                        s = this._p[3];
                    this._precomputed = !0, (t !== e || r !== s) && this._calcSampleValues() }, _calcSampleValues: function() { for (var t = this._p[0], e = this._p[2], r = 0; u > r; ++r) this._mSampleValues[r] = i(r * y, t, e) }, _getTForX: function(t) { for (var e = this._p[0], r = this._p[2], s = this._mSampleValues, i = 0, h = 1, l = u - 1; h !== l && s[h] <= t; ++h) i += y;--h; var p = (t - s[h]) / (s[h + 1] - s[h]),
                        m = i + p * y,
                        c = a(m, e, r); return c >= f ? o(t, m, e, r) : 0 === c ? m : n(t, i, i + y, e, r) } }, l }(),
        MatrixManager = matrixManagerFunction;
    ! function() { for (var t = 0, e = ["ms", "moz", "webkit", "o"], r = 0; r < e.length && !window.requestAnimationFrame; ++r) window.requestAnimationFrame = window[e[r] + "RequestAnimationFrame"], window.cancelAnimationFrame = window[e[r] + "CancelAnimationFrame"] || window[e[r] + "CancelRequestAnimationFrame"];
        window.requestAnimationFrame || (window.requestAnimationFrame = function(e, r) { var s = (new Date).getTime(),
                i = Math.max(0, 16 - (s - t)),
                a = window.setTimeout(function() { e(s + i) }, i); return t = s + i, a }), window.cancelAnimationFrame || (window.cancelAnimationFrame = function(t) { clearTimeout(t) }) }();
    var bez = bezFunction(),
        dataManager = dataFunctionManager(),
        FontManager = function() {
            function t(t, e) { var r = document.createElement("span");
                r.style.fontFamily = e; var s = document.createElement("span");
                s.innerHTML = "giItT1WQy@!-/#", r.style.position = "absolute", r.style.left = "-10000px", r.style.top = "-10000px", r.style.fontSize = "300px", r.style.fontVariant = "normal", r.style.fontStyle = "normal", r.style.fontWeight = "normal", r.style.letterSpacing = "0", r.appendChild(s), document.body.appendChild(r); var i = s.offsetWidth; return s.style.fontFamily = t + ", " + e, { node: s, w: i, parent: r } }

            function e() { var t, r, s, i = this.fonts.length,
                    a = i; for (t = 0; i > t; t += 1)
                    if (this.fonts[t].loaded) a -= 1;
                    else if ("t" === this.fonts[t].fOrigin) { if (window.Typekit && window.Typekit.load && 0 === this.typekitLoaded) { this.typekitLoaded = 1; try { window.Typekit.load({ async: !0, active: function() { this.typekitLoaded = 2 }.bind(this) }) } catch (n) {} }
                    2 === this.typekitLoaded && (this.fonts[t].loaded = !0) } else "n" === this.fonts[t].fOrigin ? this.fonts[t].loaded = !0 : (r = this.fonts[t].monoCase.node, s = this.fonts[t].monoCase.w, r.offsetWidth !== s ? (a -= 1, this.fonts[t].loaded = !0) : (r = this.fonts[t].sansCase.node, s = this.fonts[t].sansCase.w, r.offsetWidth !== s && (a -= 1, this.fonts[t].loaded = !0)), this.fonts[t].loaded && (this.fonts[t].sansCase.parent.parentNode.removeChild(this.fonts[t].sansCase.parent), this.fonts[t].monoCase.parent.parentNode.removeChild(this.fonts[t].monoCase.parent)));
                0 !== a && Date.now() - this.initTime < h ? setTimeout(e.bind(this), 20) : setTimeout(function() { this.loaded = !0 }.bind(this), 0) }

            function r(t, e) { var r = document.createElementNS(svgNS, "text");
                r.style.fontSize = "100px", r.style.fontFamily = e.fFamily, r.textContent = "1", e.fClass ? (r.style.fontFamily = "inherit", r.className = e.fClass) : r.style.fontFamily = e.fFamily, t.appendChild(r); var s = document.createElement("canvas").getContext("2d"); return s.font = "100px " + e.fFamily, s }

            function s(s, i) { if (!s) return void(this.loaded = !0); if (this.chars) return this.loaded = !0, void(this.fonts = s.list); var a, n = s.list,
                    o = n.length; for (a = 0; o > a; a += 1) { if (n[a].loaded = !1, n[a].monoCase = t(n[a].fFamily, "monospace"), n[a].sansCase = t(n[a].fFamily, "sans-serif"), n[a].fPath) { if ("p" === n[a].fOrigin) { var h = document.createElement("style");
                            h.type = "text/css", h.innerHTML = "@font-face {font-family: " + n[a].fFamily + "; font-style: normal; src: url('" + n[a].fPath + "');}", i.appendChild(h) } else if ("g" === n[a].fOrigin) { var l = document.createElement("link");
                            l.type = "text/css", l.rel = "stylesheet", l.href = n[a].fPath, i.appendChild(l) } else if ("t" === n[a].fOrigin) { var p = document.createElement("script");
                            p.setAttribute("src", n[a].fPath), i.appendChild(p) } } else n[a].loaded = !0;
                    n[a].helper = r(i, n[a]), this.fonts.push(n[a]) }
                e.bind(this)() }

            function i(t) { if (t) { this.chars || (this.chars = []); var e, r, s, i = t.length,
                        a = this.chars.length; for (e = 0; i > e; e += 1) { for (r = 0, s = !1; a > r;) this.chars[r].style === t[e].style && this.chars[r].fFamily === t[e].fFamily && this.chars[r].ch === t[e].ch && (s = !0), r += 1;
                        s || (this.chars.push(t[e]), a += 1) } } }

            function a(t, e, r) { for (var s = 0, i = this.chars.length; i > s;) { if (this.chars[s].ch === t && this.chars[s].style === e && this.chars[s].fFamily === r) return this.chars[s];
                    s += 1 } }

            function n(t, e, r) { var s = this.getFontByName(e),
                    i = s.helper; return i.measureText(t).width * r / 100 }

            function o(t) { for (var e = 0, r = this.fonts.length; r > e;) { if (this.fonts[e].fName === t) return this.fonts[e];
                    e += 1 } return "sans-serif" } var h = 5e3,
                l = function() { this.fonts = [], this.chars = null, this.typekitLoaded = 0, this.loaded = !1, this.initTime = Date.now() }; return l.prototype.addChars = i, l.prototype.addFonts = s, l.prototype.getCharData = a, l.prototype.getFontByName = o, l.prototype.measureText = n, l }(),
        PropertyFactory = function() {
            function t() { if (this.elem.globalData.frameId !== this.frameId) { this.mdf = !1; var t = this.comp.renderedFrame - this.offsetTime; if (!(t === this.lastFrame || this.lastFrame !== l && (this.lastFrame >= this.keyframes[this.keyframes.length - 1].t - this.offsetTime && t >= this.keyframes[this.keyframes.length - 1].t - this.offsetTime || this.lastFrame < this.keyframes[0].t - this.offsetTime && t < this.keyframes[0].t - this.offsetTime))) { for (var e, r, s = this.lastFrame < t ? this._lastIndex : 0, i = this.keyframes.length - 1, a = !0; a;) { if (e = this.keyframes[s], r = this.keyframes[s + 1], s == i - 1 && t >= r.t - this.offsetTime) { e.h && (e = r); break } if (r.t - this.offsetTime > t) break;
                            i - 1 > s ? s += 1 : a = !1 }
                        this._lastIndex = s; var n, o, h, p, m, f; if (e.to) { e.bezierData || bez.buildBezierData(e); var c = e.bezierData; if (t >= r.t - this.offsetTime || t < e.t - this.offsetTime) { var d = t >= r.t - this.offsetTime ? c.points.length - 1 : 0; for (o = c.points[d].point.length, n = 0; o > n; n += 1) this.pv[n] = c.points[d].point[n], this.v[n] = this.mult ? this.pv[n] * this.mult : this.pv[n], this.lastPValue[n] !== this.pv[n] && (this.mdf = !0, this.lastPValue[n] = this.pv[n]);
                                this._lastBezierData = null } else { e.__fnct ? f = e.__fnct : (f = BezierFactory.getBezierEasing(e.o.x, e.o.y, e.i.x, e.i.y, e.n).get, e.__fnct = f), h = f((t - (e.t - this.offsetTime)) / (r.t - this.offsetTime - (e.t - this.offsetTime))); var u, y = c.segmentLength * h,
                                    g = this.lastFrame < t && this._lastBezierData === c ? this._lastAddedLength : 0; for (m = this.lastFrame < t && this._lastBezierData === c ? this._lastPoint : 0, a = !0, p = c.points.length; a;) { if (g += c.points[m].partialLength, 0 === y || 0 === h || m == c.points.length - 1) { for (o = c.points[m].point.length, n = 0; o > n; n += 1) this.pv[n] = c.points[m].point[n], this.v[n] = this.mult ? this.pv[n] * this.mult : this.pv[n], this.lastPValue[n] !== this.pv[n] && (this.mdf = !0, this.lastPValue[n] = this.pv[n]); break } if (y >= g && y < g + c.points[m + 1].partialLength) { for (u = (y - g) / c.points[m + 1].partialLength, o = c.points[m].point.length, n = 0; o > n; n += 1) this.pv[n] = c.points[m].point[n] + (c.points[m + 1].point[n] - c.points[m].point[n]) * u, this.v[n] = this.mult ? this.pv[n] * this.mult : this.pv[n], this.lastPValue[n] !== this.pv[n] && (this.mdf = !0, this.lastPValue[n] = this.pv[n]); break }
                                    p - 1 > m ? m += 1 : a = !1 }
                                this._lastPoint = m, this._lastAddedLength = g - c.points[m].partialLength, this._lastBezierData = c } } else { var v, b, E, P, x; for (i = e.s.length, s = 0; i > s; s += 1) { if (1 !== e.h && (t >= r.t - this.offsetTime ? h = 1 : t < e.t - this.offsetTime ? h = 0 : (e.o.x instanceof Array ? (e.__fnct || (e.__fnct = []), e.__fnct[s] ? f = e.__fnct[s] : (v = e.o.x[s] || e.o.x[0], b = e.o.y[s] || e.o.y[0], E = e.i.x[s] || e.i.x[0], P = e.i.y[s] || e.i.y[0], f = BezierFactory.getBezierEasing(v, b, E, P).get, e.__fnct[s] = f)) : e.__fnct ? f = e.__fnct : (v = e.o.x, b = e.o.y, E = e.i.x, P = e.i.y, f = BezierFactory.getBezierEasing(v, b, E, P).get, e.__fnct = f), h = f((t - (e.t - this.offsetTime)) / (r.t - this.offsetTime - (e.t - this.offsetTime))))), this.sh && 1 !== e.h) { var S = e.s[s],
                                        C = e.e[s]; - 180 > S - C ? S += 360 : S - C > 180 && (S -= 360), x = S + (C - S) * h } else x = 1 === e.h ? e.s[s] : e.s[s] + (e.e[s] - e.s[s]) * h;
                                1 === i ? (this.v = this.mult ? x * this.mult : x, this.pv = x, this.lastPValue != this.pv && (this.mdf = !0, this.lastPValue = this.pv)) : (this.v[s] = this.mult ? x * this.mult : x, this.pv[s] = x, this.lastPValue[s] !== this.pv[s] && (this.mdf = !0, this.lastPValue[s] = this.pv[s])) } } }
                    this.lastFrame = t, this.frameId = this.elem.globalData.frameId } }

            function e() {}

            function r(t, r, s) { this.mult = s, this.v = s ? r.k * s : r.k, this.pv = r.k, this.mdf = !1, this.comp = t.comp, this.k = !1, this.kf = !1, this.vel = 0, this.getValue = e }

            function s(t, r, s) { this.mult = s, this.data = r, this.mdf = !1, this.comp = t.comp, this.k = !1, this.kf = !1, this.frameId = -1, this.v = Array.apply(null, { length: r.k.length }), this.pv = Array.apply(null, { length: r.k.length }), this.lastValue = Array.apply(null, { length: r.k.length }); var i = Array.apply(null, { length: r.k.length });
                this.vel = i.map(function() { return 0 }); var a, n = r.k.length; for (a = 0; n > a; a += 1) this.v[a] = s ? r.k[a] * s : r.k[a], this.pv[a] = r.k[a];
                this.getValue = e }

            function i(e, r, s) { this.keyframes = r.k, this.offsetTime = e.data.st, this.lastValue = -99999, this.lastPValue = -99999, this.frameId = -1, this._lastIndex = 0, this.k = !0, this.kf = !0, this.data = r, this.mult = s, this.elem = e, this.comp = e.comp, this.lastFrame = l, this.v = s ? r.k[0].s[0] * s : r.k[0].s[0], this.pv = r.k[0].s[0], this.getValue = t }

            function a(e, r, s) { var i, a, n, o, h, p = r.k.length; for (i = 0; p - 1 > i; i += 1) r.k[i].to && r.k[i].s && r.k[i].e && (a = r.k[i].s, n = r.k[i].e, o = r.k[i].to, h = r.k[i].ti, (2 === a.length && (a[0] !== n[0] || a[1] !== n[1]) && bez.pointOnLine2D(a[0], a[1], n[0], n[1], a[0] + o[0], a[1] + o[1]) && bez.pointOnLine2D(a[0], a[1], n[0], n[1], n[0] + h[0], n[1] + h[1]) || 3 === a.length && (a[0] !== n[0] || a[1] !== n[1] || a[2] !== n[2]) && bez.pointOnLine3D(a[0], a[1], a[2], n[0], n[1], n[2], a[0] + o[0], a[1] + o[1], a[2] + o[2]) && bez.pointOnLine3D(a[0], a[1], a[2], n[0], n[1], n[2], n[0] + h[0], n[1] + h[1], n[2] + h[2])) && (r.k[i].to = null, r.k[i].ti = null));
                this.keyframes = r.k, this.offsetTime = e.data.st, this.k = !0, this.kf = !0, this.mult = s, this.elem = e, this.comp = e.comp, this.getValue = t, this.frameId = -1, this._lastIndex = 0, this.v = Array.apply(null, { length: r.k[0].s.length }), this.pv = Array.apply(null, { length: r.k[0].s.length }), this.lastValue = Array.apply(null, { length: r.k[0].s.length }), this.lastPValue = Array.apply(null, { length: r.k[0].s.length }), this.lastFrame = l }

            function n(t, e, n, o, h) { var l; if (2 === n) l = new p(t, e, h);
                else if (0 === e.a) l = 0 === n ? new r(t, e, o) : new s(t, e, o);
                else if (1 === e.a) l = 0 === n ? new i(t, e, o) : new a(t, e, o);
                else if (e.k.length)
                    if ("number" == typeof e.k[0]) l = new s(t, e, o);
                    else switch (n) {
                        case 0:
                            l = new i(t, e, o); break;
                        case 1:
                            l = new a(t, e, o) } else l = new r(t, e, o);
                return l.k && h.push(l), l }

            function o(t, e, r, s) { return new f(t, e, r, s) }

            function h(t, e, r) { return new c(t, e, r) } var l = -999999,
                p = function() {
                    function t() { return ExpressionValue(this.p) }

                    function e() { return ExpressionValue(this.px) }

                    function r() { return ExpressionValue(this.py) }

                    function s() { return ExpressionValue(this.a) }

                    function i() { return ExpressionValue(this.or) }

                    function a() { return ExpressionValue(this.r, 1 / degToRads) }

                    function n() { return ExpressionValue(this.s, 100) }

                    function o() { return ExpressionValue(this.o, 100) }

                    function h() { return ExpressionValue(this.sk) }

                    function l() { return ExpressionValue(this.sa) }

                    function p(t) { var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(), this.dynamicProperties[e].mdf && (this.mdf = !0);
                        this.a && t.translate(-this.a.v[0], -this.a.v[1], this.a.v[2]), this.s && t.scale(this.s.v[0], this.s.v[1], this.s.v[2]), this.r ? t.rotate(-this.r.v) : t.rotateZ(-this.rz.v).rotateY(this.ry.v).rotateX(this.rx.v).rotateZ(-this.or.v[2]).rotateY(this.or.v[1]).rotateX(this.or.v[0]), this.data.p.s ? this.data.p.z ? t.translate(this.px.v, this.py.v, -this.pz.v) : t.translate(this.px.v, this.py.v, 0) : t.translate(this.p.v[0], this.p.v[1], -this.p.v[2]) }

                    function m() { if (this.elem.globalData.frameId !== this.frameId) { this.mdf = !1; var t, e = this.dynamicProperties.length; for (t = 0; e > t; t += 1) this.dynamicProperties[t].getValue(), this.dynamicProperties[t].mdf && (this.mdf = !0); if (this.mdf) { if (this.v.reset(), this.a && this.v.translate(-this.a.v[0], -this.a.v[1], this.a.v[2]), this.s && this.v.scale(this.s.v[0], this.s.v[1], this.s.v[2]), this.sk && this.v.skewFromAxis(-this.sk.v, this.sa.v), this.r ? this.v.rotate(-this.r.v) : this.v.rotateZ(-this.rz.v).rotateY(this.ry.v).rotateX(this.rx.v).rotateZ(-this.or.v[2]).rotateY(this.or.v[1]).rotateX(this.or.v[0]), this.autoOriented && this.p.keyframes && this.p.getValueAtTime) { var r, s;
                                    this.p.lastFrame + this.p.offsetTime <= this.p.keyframes[0].t ? (r = this.p.getValueAtTime((this.p.keyframes[0].t + .01) / this.elem.globalData.frameRate, 0), s = this.p.getValueAtTime(this.p.keyframes[0].t / this.elem.globalData.frameRate, 0)) : this.p.lastFrame + this.p.offsetTime >= this.p.keyframes[this.p.keyframes.length - 1].t ? (r = this.p.getValueAtTime(this.p.keyframes[this.p.keyframes.length - 1].t / this.elem.globalData.frameRate, 0), s = this.p.getValueAtTime((this.p.keyframes[this.p.keyframes.length - 1].t - .01) / this.elem.globalData.frameRate, 0)) : (r = this.p.pv, s = this.p.getValueAtTime((this.p.lastFrame + this.p.offsetTime - .01) / this.elem.globalData.frameRate, this.p.offsetTime)), this.v.rotate(-Math.atan2(r[1] - s[1], r[0] - s[0])) }
                                this.data.p.s ? this.data.p.z ? this.v.translate(this.px.v, this.py.v, -this.pz.v) : this.v.translate(this.px.v, this.py.v, 0) : this.v.translate(this.p.v[0], this.p.v[1], -this.p.v[2]) }
                            this.frameId = this.elem.globalData.frameId } }

                    function f() { this.inverted = !0, this.iv = new Matrix, this.k || (this.data.p.s ? this.iv.translate(this.px.v, this.py.v, -this.pz.v) : this.iv.translate(this.p.v[0], this.p.v[1], -this.p.v[2]), this.r ? this.iv.rotate(-this.r.v) : this.iv.rotateX(-this.rx.v).rotateY(-this.ry.v).rotateZ(this.rz.v), this.s && this.iv.scale(this.s.v[0], this.s.v[1], 1), this.a && this.iv.translate(-this.a.v[0], -this.a.v[1], this.a.v[2])) }

                    function c() {} return function(d, u, y) { this.elem = d, this.frameId = -1, this.type = "transform", this.dynamicProperties = [], this.mdf = !1, this.data = u, this.getValue = m, this.applyToMatrix = p, this.setInverted = f, this.autoOrient = c, this.v = new Matrix, u.p.s ? (this.px = PropertyFactory.getProp(d, u.p.x, 0, 0, this.dynamicProperties), this.py = PropertyFactory.getProp(d, u.p.y, 0, 0, this.dynamicProperties), u.p.z && (this.pz = PropertyFactory.getProp(d, u.p.z, 0, 0, this.dynamicProperties))) : this.p = PropertyFactory.getProp(d, u.p, 1, 0, this.dynamicProperties), u.r ? this.r = PropertyFactory.getProp(d, u.r, 0, degToRads, this.dynamicProperties) : u.rx && (this.rx = PropertyFactory.getProp(d, u.rx, 0, degToRads, this.dynamicProperties), this.ry = PropertyFactory.getProp(d, u.ry, 0, degToRads, this.dynamicProperties), this.rz = PropertyFactory.getProp(d, u.rz, 0, degToRads, this.dynamicProperties), this.or = PropertyFactory.getProp(d, u.or, 1, degToRads, this.dynamicProperties)), u.sk && (this.sk = PropertyFactory.getProp(d, u.sk, 0, degToRads, this.dynamicProperties), this.sa = PropertyFactory.getProp(d, u.sa, 0, degToRads, this.dynamicProperties)), u.a && (this.a = PropertyFactory.getProp(d, u.a, 1, 0, this.dynamicProperties)), u.s && (this.s = PropertyFactory.getProp(d, u.s, 1, .01, this.dynamicProperties)), this.o = u.o ? PropertyFactory.getProp(d, u.o, 0, .01, y) : { mdf: !1, v: 1 }, this.dynamicProperties.length ? y.push(this) : (this.a && this.v.translate(-this.a.v[0], -this.a.v[1], this.a.v[2]), this.s && this.v.scale(this.s.v[0], this.s.v[1], this.s.v[2]), this.sk && this.v.skewFromAxis(-this.sk.v, this.sa.v), this.r ? this.v.rotate(-this.r.v) : this.v.rotateZ(-this.rz.v).rotateY(this.ry.v).rotateX(this.rx.v).rotateZ(-this.or.v[2]).rotateY(this.or.v[1]).rotateX(this.or.v[0]), this.data.p.s ? u.p.z ? this.v.translate(this.px.v, this.py.v, -this.pz.v) : this.v.translate(this.px.v, this.py.v, 0) : this.v.translate(this.p.v[0], this.p.v[1], -this.p.v[2])), Object.defineProperty(this, "position", { get: t }), Object.defineProperty(this, "xPosition", { get: e }), Object.defineProperty(this, "yPosition", { get: r }), Object.defineProperty(this, "orientation", { get: i }), Object.defineProperty(this, "anchorPoint", { get: s }), Object.defineProperty(this, "rotation", { get: a }), Object.defineProperty(this, "scale", { get: n }), Object.defineProperty(this, "opacity", { get: o }), Object.defineProperty(this, "skew", { get: h }), Object.defineProperty(this, "skewAxis", { get: l }) } }(),
                m = function() {
                    function t(t) { if (this.prop.getValue(), this.cmdf = !1, this.omdf = !1, this.prop.mdf || t) { var e, r, s, i = 4 * this.data.p; for (e = 0; i > e; e += 1) r = e % 4 === 0 ? 100 : 255, s = Math.round(this.prop.v[e] * r), this.c[e] !== s && (this.c[e] = s, this.cmdf = !0); if (this.o.length)
                                for (i = this.prop.v.length, e = 4 * this.data.p; i > e; e += 1) r = e % 2 === 0 ? 100 : 1, s = e % 2 === 0 ? Math.round(100 * this.prop.v[e]) : this.prop.v[e], this.o[e - 4 * this.data.p] !== s && (this.o[e - 4 * this.data.p] = s, this.omdf = !0) } }

                    function e(e, r, s) { this.prop = n(e, r.k, 1, null, []), this.data = r, this.k = this.prop.k, this.c = Array.apply(null, { length: 4 * r.p }); var i = r.k.k[0].s ? r.k.k[0].s.length - 4 * r.p : r.k.k.length - 4 * r.p;
                        this.o = Array.apply(null, { length: i }), this.cmdf = !1, this.omdf = !1, this.getValue = t, this.prop.k && s.push(this), this.getValue(!0) } return function(t, r, s) { return new e(t, r, s) } }(),
                f = function() {
                    function t(t) { var e = 0,
                            r = this.dataProps.length; if (this.elem.globalData.frameId !== this.frameId || t) { for (this.mdf = !1, this.frameId = this.elem.globalData.frameId; r > e;) { if (this.dataProps[e].p.mdf) { this.mdf = !0; break }
                                e += 1 } if (this.mdf || t)
                                for ("svg" === this.renderer && (this.dasharray = ""), e = 0; r > e; e += 1) "o" != this.dataProps[e].n ? "svg" === this.renderer ? this.dasharray += " " + this.dataProps[e].p.v : this.dasharray[e] = this.dataProps[e].p.v : this.dashoffset = this.dataProps[e].p.v } } return function(e, r, s, i) { this.elem = e, this.frameId = -1, this.dataProps = new Array(r.length), this.renderer = s, this.mdf = !1, this.k = !1, this.dasharray = "svg" === this.renderer ? "" : new Array(r.length - 1), this.dashoffset = 0; var a, n, o = r.length; for (a = 0; o > a; a += 1) n = PropertyFactory.getProp(e, r[a].v, 0, 0, i), this.k = n.k ? !0 : this.k, this.dataProps[a] = { n: r[a].n, p: n };
                        this.getValue = t, this.k ? i.push(this) : this.getValue(!0) } }(),
                c = function() {
                    function t() { if (this.dynamicProperties.length) { var t, e = this.dynamicProperties.length; for (t = 0; e > t; t += 1) this.dynamicProperties[t].getValue(), this.dynamicProperties[t].mdf && (this.mdf = !0) } var r = this.data.totalChars,
                            s = 2 === this.data.r ? 1 : 100 / r,
                            i = this.o.v / s,
                            a = this.s.v / s + i,
                            n = this.e.v / s + i; if (a > n) { var o = a;
                            a = n, n = o }
                        this.finalS = a, this.finalE = n }

                    function e(t) { var e = BezierFactory.getBezierEasing(this.ne.v / 100, 0, 1 - this.xe.v / 100, 1).get,
                            a = 0,
                            n = this.finalS,
                            o = this.finalE,
                            h = this.data.sh; if (2 == h) a = o === n ? t >= o ? 1 : 0 : r(0, s(.5 / (o - n) + (t - n) / (o - n), 1)), a = e(a);
                        else if (3 == h) a = o === n ? t >= o ? 0 : 1 : 1 - r(0, s(.5 / (o - n) + (t - n) / (o - n), 1)), a = e(a);
                        else if (4 == h) o === n ? a = 0 : (a = r(0, s(.5 / (o - n) + (t - n) / (o - n), 1)), .5 > a ? a *= 2 : a = 1 - 2 * (a - .5)), a = e(a);
                        else if (5 == h) { if (o === n) a = 0;
                            else { var l = o - n;
                                t = s(r(0, t + .5 - n), o - n); var p = -l / 2 + t,
                                    m = l / 2;
                                a = Math.sqrt(1 - p * p / (m * m)) }
                            a = e(a) } else 6 == h ? (o === n ? a = 0 : (t = s(r(0, t + .5 - n), o - n), a = (1 + Math.cos(Math.PI + 2 * Math.PI * t / (o - n))) / 2), a = e(a)) : (t >= i(n) && (a = 0 > t - n ? 1 - (n - t) : r(0, s(o - t, 1))), a = e(a)); return a * this.a.v } var r = Math.max,
                        s = Math.min,
                        i = Math.floor; return function(r, s, i) { this.mdf = !1, this.k = !1, this.data = s, this.dynamicProperties = [], this.getValue = t, this.getMult = e, this.comp = r.comp, this.finalS = 0, this.finalE = 0, this.s = PropertyFactory.getProp(r, s.s || { k: 0 }, 0, 0, this.dynamicProperties), this.e = "e" in s ? PropertyFactory.getProp(r, s.e, 0, 0, this.dynamicProperties) : { v: 2 === s.r ? s.totalChars : 100 }, this.o = PropertyFactory.getProp(r, s.o || { k: 0 }, 0, 0, this.dynamicProperties), this.xe = PropertyFactory.getProp(r, s.xe || { k: 0 }, 0, 0, this.dynamicProperties), this.ne = PropertyFactory.getProp(r, s.ne || { k: 0 }, 0, 0, this.dynamicProperties), this.a = PropertyFactory.getProp(r, s.a, 0, .01, this.dynamicProperties), this.dynamicProperties.length ? i.push(this) : this.getValue() } }(),
                d = {}; return d.getProp = n, d.getDashProp = o, d.getTextSelectorProp = h, d.getGradientProp = m, d }();
    ShapePath.prototype.setPathData = function(t, e) { for (this.c = t; e > this._maxLength;) this.doubleArrayLength(); for (var r = 0; e > r;) this.v[r] = point_pool.newPoint(), this.o[r] = point_pool.newPoint(), this.i[r] = point_pool.newPoint(), r += 1;
        this._length = e }, ShapePath.prototype.doubleArrayLength = function() { this.v = this.v.concat(Array.apply(null, { length: this._maxLength })), this.i = this.i.concat(Array.apply(null, { length: this._maxLength })), this.o = this.o.concat(Array.apply(null, { length: this._maxLength })), this._maxLength *= 2 }, ShapePath.prototype.setXYAt = function(t, e, r, s, i) { var a; switch (this._length = Math.max(this._length, s + 1), this._length >= this._maxLength && this.doubleArrayLength(), r) {
            case "v":
                a = this.v; break;
            case "i":
                a = this.i; break;
            case "o":
                a = this.o }(!a[s] || a[s] && !i) && (a[s] = point_pool.newPoint()), a[s][0] = t, a[s][1] = e }, ShapePath.prototype.setTripleAt = function(t, e, r, s, i, a, n, o) { this.setXYAt(t, e, "v", n, o), this.setXYAt(r, s, "o", n, o), this.setXYAt(i, a, "i", n, o) };
    var ShapePropertyFactory = function() {
            function t() { if (this.elem.globalData.frameId !== this.frameId) { this.mdf = !1; var t = this.comp.renderedFrame - this.offsetTime; if (this.lastFrame === n || !(this.lastFrame < this.keyframes[0].t - this.offsetTime && t < this.keyframes[0].t - this.offsetTime || this.lastFrame > this.keyframes[this.keyframes.length - 1].t - this.offsetTime && t > this.keyframes[this.keyframes.length - 1].t - this.offsetTime)) { var e, r, s; if (t < this.keyframes[0].t - this.offsetTime) e = this.keyframes[0].s[0], s = !0, this._lastIndex = 0;
                        else if (t >= this.keyframes[this.keyframes.length - 1].t - this.offsetTime) e = 1 === this.keyframes[this.keyframes.length - 2].h ? this.keyframes[this.keyframes.length - 1].s[0] : this.keyframes[this.keyframes.length - 2].e[0], s = !0;
                        else { for (var i, a, o, h, l, p, m = this.lastFrame < n ? this._lastIndex : 0, f = this.keyframes.length - 1, c = !0; c && (i = this.keyframes[m], a = this.keyframes[m + 1], !(a.t - this.offsetTime > t));) f - 1 > m ? m += 1 : c = !1;
                            s = 1 === i.h, this._lastIndex = m; var d; if (!s) { if (t >= a.t - this.offsetTime) d = 1;
                                else if (t < i.t - this.offsetTime) d = 0;
                                else { var u;
                                    i.__fnct ? u = i.__fnct : (u = BezierFactory.getBezierEasing(i.o.x, i.o.y, i.i.x, i.i.y).get, i.__fnct = u), d = u((t - (i.t - this.offsetTime)) / (a.t - this.offsetTime - (i.t - this.offsetTime))) }
                                r = i.e[0] }
                            e = i.s[0] }
                        h = this.v._length, p = e.i[0].length; var y, g = !1; for (o = 0; h > o; o += 1)
                            for (l = 0; p > l; l += 1) s ? (y = e.i[o][l], this.v.i[o][l] !== y && (this.v.i[o][l] = y, this.pv.i[o][l] = y, g = !0), y = e.o[o][l], this.v.o[o][l] !== y && (this.v.o[o][l] = y, this.pv.o[o][l] = y, g = !0), y = e.v[o][l], this.v.v[o][l] !== y && (this.v.v[o][l] = y, this.pv.v[o][l] = y, g = !0)) : (y = e.i[o][l] + (r.i[o][l] - e.i[o][l]) * d, this.v.i[o][l] !== y && (this.v.i[o][l] = y, this.pv.i[o][l] = y, g = !0), y = e.o[o][l] + (r.o[o][l] - e.o[o][l]) * d, this.v.o[o][l] !== y && (this.v.o[o][l] = y, this.pv.o[o][l] = y, g = !0), y = e.v[o][l] + (r.v[o][l] - e.v[o][l]) * d, this.v.v[o][l] !== y && (this.v.v[o][l] = y, this.pv.v[o][l] = y, g = !0));
                        this.mdf = g, this.v.c = e.c, this.paths = this.localShapeCollection }
                    this.lastFrame = t, this.frameId = this.elem.globalData.frameId } }

            function e() { return this.v }

            function r() { this.paths = this.localShapeCollection, this.k || (this.mdf = !1) }

            function s(t, s, i) { this.comp = t.comp, this.k = !1, this.mdf = !1, this.v = shape_pool.newShape(); var a = 3 === i ? s.pt.k : s.ks.k;
                this.v.v = a.v, this.v.i = a.i, this.v.o = a.o, this.v.c = a.c, this.v._length = this.v.v.length, this.getValue = e, this.pv = shape_pool.clone(this.v), this.localShapeCollection = shapeCollection_pool.newShapeCollection(), this.paths = this.localShapeCollection, this.paths.addShape(this.v), this.reset = r }

            function i(e, s, i) { this.comp = e.comp, this.elem = e, this.offsetTime = e.data.st, this._lastIndex = 0, this.getValue = t, this.keyframes = 3 === i ? s.pt.k : s.ks.k, this.k = !0; { var a = this.keyframes[0].s[0].i.length;
                    this.keyframes[0].s[0].i[0].length }
                this.v = shape_pool.newShape(), this.v.setPathData(this.keyframes[0].s[0].c, a), this.pv = shape_pool.clone(this.v), this.localShapeCollection = shapeCollection_pool.newShapeCollection(), this.paths = this.localShapeCollection, this.paths.addShape(this.v), this.lastFrame = n, this.reset = r }

            function a(t, e, r, a) { var n; if (3 === r || 4 === r) { var p = 3 === r ? e.pt : e.ks,
                        m = p.k;
                    n = 1 === p.a || m.length ? new i(t, e, r) : new s(t, e, r) } else 5 === r ? n = new l(t, e) : 6 === r ? n = new o(t, e) : 7 === r && (n = new h(t, e)); return n.k && a.push(n), n }
            var n = -999999,
                o = function() {
                    function t() {
                        var t = this.p.v[0],
                            e = this.p.v[1],
                            r = this.s.v[0] / 2,
                            i = this.s.v[1] / 2;
                        3 !== this.d ? (this.v.v[0][0] = t, this.v.v[0][1] = e - i, this.v.v[1][0] = t + r, this.v.v[1][1] = e, this.v.v[2][0] = t, this.v.v[2][1] = e + i, this.v.v[3][0] = t - r, this.v.v[3][1] = e, this.v.i[0][0] = t - r * s, this.v.i[0][1] = e - i, this.v.i[1][0] = t + r, this.v.i[1][1] = e - i * s, this.v.i[2][0] = t + r * s, this.v.i[2][1] = e + i, this.v.i[3][0] = t - r, this.v.i[3][1] = e + i * s, this.v.o[0][0] = t + r * s, this.v.o[0][1] = e - i, this.v.o[1][0] = t + r, this.v.o[1][1] = e + i * s, this.v.o[2][0] = t - r * s, this.v.o[2][1] = e + i, this.v.o[3][0] = t - r, this.v.o[3][1] = e - i * s) : (this.v.v[0][0] = t, this.v.v[0][1] = e - i, this.v.v[1][0] = t - r, this.v.v[1][1] = e, this.v.v[2][0] = t, this.v.v[2][1] = e + i, this.v.v[3][0] = t + r, this.v.v[3][1] = e, this.v.i[0][0] = t + r * s, this.v.i[0][1] = e - i, this.v.i[1][0] = t - r, this.v.i[1][1] = e - i * s, this.v.i[2][0] = t - r * s, this.v.i[2][1] = e + i, this.v.i[3][0] = t + r, this.v.i[3][1] = e + i * s,
                            this.v.o[0][0] = t - r * s, this.v.o[0][1] = e - i, this.v.o[1][0] = t - r, this.v.o[1][1] = e + i * s, this.v.o[2][0] = t + r * s, this.v.o[2][1] = e + i, this.v.o[3][0] = t + r, this.v.o[3][1] = e - i * s)
                    }

                    function e(t) { var e, r = this.dynamicProperties.length; if (this.elem.globalData.frameId !== this.frameId) { for (this.mdf = !1, this.frameId = this.elem.globalData.frameId, e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(t), this.dynamicProperties[e].mdf && (this.mdf = !0);
                            this.mdf && this.convertEllToPath() } }
                    var s = roundCorner;
                    return function(s, i) { this.v = shape_pool.newShape(), this.v.setPathData(!0, 4), this.localShapeCollection = shapeCollection_pool.newShapeCollection(), this.paths = this.localShapeCollection, this.localShapeCollection.addShape(this.v), this.d = i.d, this.dynamicProperties = [], this.elem = s, this.comp = s.comp, this.frameId = -1, this.mdf = !1, this.getValue = e, this.convertEllToPath = t, this.reset = r, this.p = PropertyFactory.getProp(s, i.p, 1, 0, this.dynamicProperties), this.s = PropertyFactory.getProp(s, i.s, 1, 0, this.dynamicProperties), this.dynamicProperties.length ? this.k = !0 : this.convertEllToPath() }
                }(),
                h = function() {
                    function t() { var t, e = Math.floor(this.pt.v),
                            r = 2 * Math.PI / e,
                            s = this.or.v,
                            i = this.os.v,
                            a = 2 * Math.PI * s / (4 * e),
                            n = -Math.PI / 2,
                            o = 3 === this.data.d ? -1 : 1; for (n += this.r.v, this.v._length = 0, t = 0; e > t; t += 1) { var h = s * Math.cos(n),
                                l = s * Math.sin(n),
                                p = 0 === h && 0 === l ? 0 : l / Math.sqrt(h * h + l * l),
                                m = 0 === h && 0 === l ? 0 : -h / Math.sqrt(h * h + l * l);
                            h += +this.p.v[0], l += +this.p.v[1], this.v.setTripleAt(h, l, h - p * a * i * o, l - m * a * i * o, h + p * a * i * o, l + m * a * i * o, t, !0), n += r * o }
                        this.paths.length = 0, this.paths[0] = this.v }

                    function e() { var t, e, r, s, i = 2 * Math.floor(this.pt.v),
                            a = 2 * Math.PI / i,
                            n = !0,
                            o = this.or.v,
                            h = this.ir.v,
                            l = this.os.v,
                            p = this.is.v,
                            m = 2 * Math.PI * o / (2 * i),
                            f = 2 * Math.PI * h / (2 * i),
                            c = -Math.PI / 2;
                        c += this.r.v; var d = 3 === this.data.d ? -1 : 1; for (this.v._length = 0, t = 0; i > t; t += 1) { e = n ? o : h, r = n ? l : p, s = n ? m : f; var u = e * Math.cos(c),
                                y = e * Math.sin(c),
                                g = 0 === u && 0 === y ? 0 : y / Math.sqrt(u * u + y * y),
                                v = 0 === u && 0 === y ? 0 : -u / Math.sqrt(u * u + y * y);
                            u += +this.p.v[0], y += +this.p.v[1], this.v.setTripleAt(u, y, u - g * s * r * d, y - v * s * r * d, u + g * s * r * d, y + v * s * r * d, t, !0), n = !n, c += a * d } }

                    function s() { if (this.elem.globalData.frameId !== this.frameId) { this.mdf = !1, this.frameId = this.elem.globalData.frameId; var t, e = this.dynamicProperties.length; for (t = 0; e > t; t += 1) this.dynamicProperties[t].getValue(), this.dynamicProperties[t].mdf && (this.mdf = !0);
                            this.mdf && this.convertToPath() } } return function(i, a) { this.v = shape_pool.newShape(), this.v.setPathData(!0, 0), this.elem = i, this.comp = i.comp, this.data = a, this.frameId = -1, this.d = a.d, this.dynamicProperties = [], this.mdf = !1, this.getValue = s, this.reset = r, 1 === a.sy ? (this.ir = PropertyFactory.getProp(i, a.ir, 0, 0, this.dynamicProperties), this.is = PropertyFactory.getProp(i, a.is, 0, .01, this.dynamicProperties), this.convertToPath = e) : this.convertToPath = t, this.pt = PropertyFactory.getProp(i, a.pt, 0, 0, this.dynamicProperties), this.p = PropertyFactory.getProp(i, a.p, 1, 0, this.dynamicProperties), this.r = PropertyFactory.getProp(i, a.r, 0, degToRads, this.dynamicProperties), this.or = PropertyFactory.getProp(i, a.or, 0, 0, this.dynamicProperties), this.os = PropertyFactory.getProp(i, a.os, 0, .01, this.dynamicProperties), this.localShapeCollection = shapeCollection_pool.newShapeCollection(), this.localShapeCollection.addShape(this.v), this.paths = this.localShapeCollection, this.dynamicProperties.length ? this.k = !0 : this.convertToPath() } }(),
                l = function() {
                    function t(t) { if (this.elem.globalData.frameId !== this.frameId) { this.mdf = !1, this.frameId = this.elem.globalData.frameId; var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(t), this.dynamicProperties[e].mdf && (this.mdf = !0);
                            this.mdf && this.convertRectToPath() } }

                    function e() { var t = this.p.v[0],
                            e = this.p.v[1],
                            r = this.s.v[0] / 2,
                            s = this.s.v[1] / 2,
                            i = bm_min(r, s, this.r.v),
                            a = i * (1 - roundCorner);
                        this.v._length = 0, 2 === this.d || 1 === this.d ? (this.v.setTripleAt(t + r, e - s + i, t + r, e - s + i, t + r, e - s + a, 0, !0), this.v.setTripleAt(t + r, e + s - i, t + r, e + s - a, t + r, e + s - i, 1, !0), 0 !== i ? (this.v.setTripleAt(t + r - i, e + s, t + r - i, e + s, t + r - a, e + s, 2, !0), this.v.setTripleAt(t - r + i, e + s, t - r + a, e + s, t - r + i, e + s, 3, !0), this.v.setTripleAt(t - r, e + s - i, t - r, e + s - i, t - r, e + s - a, 4, !0), this.v.setTripleAt(t - r, e - s + i, t - r, e - s + a, t - r, e - s + i, 5, !0), this.v.setTripleAt(t - r + i, e - s, t - r + i, e - s, t - r + a, e - s, 6, !0), this.v.setTripleAt(t + r - i, e - s, t + r - a, e - s, t + r - i, e - s, 7, !0)) : (this.v.setTripleAt(t - r, e + s, t - r + a, e + s, t - r, e + s, 2), this.v.setTripleAt(t - r, e - s, t - r, e - s + a, t - r, e - s, 3))) : (this.v.setTripleAt(t + r, e - s + i, t + r, e - s + a, t + r, e - s + i, 0, !0), 0 !== i ? (this.v.setTripleAt(t + r - i, e - s, t + r - i, e - s, t + r - a, e - s, 1, !0), this.v.setTripleAt(t - r + i, e - s, t - r + a, e - s, t - r + i, e - s, 2, !0), this.v.setTripleAt(t - r, e - s + i, t - r, e - s + i, t - r, e - s + a, 3, !0), this.v.setTripleAt(t - r, e + s - i, t - r, e + s - a, t - r, e + s - i, 4, !0), this.v.setTripleAt(t - r + i, e + s, t - r + i, e + s, t - r + a, e + s, 5, !0), this.v.setTripleAt(t + r - i, e + s, t + r - a, e + s, t + r - i, e + s, 6, !0), this.v.setTripleAt(t + r, e + s - i, t + r, e + s - i, t + r, e + s - a, 7, !0)) : (this.v.setTripleAt(t - r, e - s, t - r + a, e - s, t - r, e - s, 1, !0), this.v.setTripleAt(t - r, e + s, t - r, e + s - a, t - r, e + s, 2, !0), this.v.setTripleAt(t + r, e + s, t + r - a, e + s, t + r, e + s, 3, !0))) } return function(s, i) { this.v = shape_pool.newShape(), this.v.c = !0, this.localShapeCollection = shapeCollection_pool.newShapeCollection(), this.localShapeCollection.addShape(this.v), this.paths = this.localShapeCollection, this.elem = s, this.comp = s.comp, this.frameId = -1, this.d = i.d, this.dynamicProperties = [], this.mdf = !1, this.getValue = t, this.convertRectToPath = e, this.reset = r, this.p = PropertyFactory.getProp(s, i.p, 1, 0, this.dynamicProperties), this.s = PropertyFactory.getProp(s, i.s, 1, 0, this.dynamicProperties), this.r = PropertyFactory.getProp(s, i.r, 0, 0, this.dynamicProperties), this.dynamicProperties.length ? this.k = !0 : this.convertRectToPath() } }(),
                p = {};
            return p.getShapeProp = a, p
        }(),
        ShapeModifiers = function() {
            function t(t, e) { s[t] || (s[t] = e) }

            function e(t, e, r, i) { return new s[t](e, r, i) } var r = {},
                s = {}; return r.registerModifier = t, r.getModifier = e, r }();
    ShapeModifier.prototype.initModifierProperties = function() {}, ShapeModifier.prototype.addShapeToModifier = function() {}, ShapeModifier.prototype.addShape = function(t) { this.closed || (this.shapes.push({ shape: t.sh, data: t, localShapeCollection: shapeCollection_pool.newShapeCollection() }), this.addShapeToModifier(t.sh)) }, ShapeModifier.prototype.init = function(t, e, r) { this.elem = t, this.frameId = -1, this.shapes = [], this.dynamicProperties = [], this.mdf = !1, this.closed = !1, this.k = !1, this.isTrimming = !1, this.comp = t.comp, this.initModifierProperties(t, e), this.dynamicProperties.length ? (this.k = !0, r.push(this)) : this.getValue(!0) }, extendPrototype(ShapeModifier, TrimModifier), TrimModifier.prototype.processKeys = function(t) { if (this.elem.globalData.frameId !== this.frameId || t) { this.mdf = t ? !0 : !1, this.frameId = this.elem.globalData.frameId; var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(), this.dynamicProperties[e].mdf && (this.mdf = !0); if (this.mdf || t) { var s = this.o.v % 360 / 360;
                0 > s && (s += 1); var i = this.s.v + s,
                    a = this.e.v + s; if (i > a) { var n = i;
                    i = a, a = n }
                this.sValue = i, this.eValue = a, this.oValue = s } } }, TrimModifier.prototype.initModifierProperties = function(t, e) { this.sValue = 0, this.eValue = 0, this.oValue = 0, this.getValue = this.processKeys, this.s = PropertyFactory.getProp(t, e.s, 0, .01, this.dynamicProperties), this.e = PropertyFactory.getProp(t, e.e, 0, .01, this.dynamicProperties), this.o = PropertyFactory.getProp(t, e.o, 0, 0, this.dynamicProperties), this.m = e.m, this.dynamicProperties.length || this.getValue(!0) }, TrimModifier.prototype.getSegmentsLength = function(t) { var e, r = t.c,
            s = t.v,
            i = t.o,
            a = t.i,
            n = t._length,
            o = [],
            h = 0; for (e = 0; n - 1 > e; e += 1) o[e] = bez.getBezierLength(s[e], s[e + 1], i[e], a[e + 1]), h += o[e].addedLength; return r && (o[e] = bez.getBezierLength(s[e], s[0], i[e], a[0]), h += o[e].addedLength), { lengths: o, totalLength: h } }, TrimModifier.prototype.calculateShapeEdges = function(t, e, r, s, i) { var a = [];
        1 >= e ? a.push({ s: t, e: e }) : t >= 1 ? a.push({ s: t - 1, e: e - 1 }) : (a.push({ s: t, e: 1 }), a.push({ s: 0, e: e - 1 })); var n, o, h = [],
            l = a.length; for (n = 0; l > n; n += 1)
            if (o = a[n], o.e * i < s || o.s * i > s + r);
            else { var p, m;
                p = o.s * i <= s ? 0 : (o.s * i - s) / r, m = o.e * i >= s + r ? 1 : (o.e * i - s) / r, h.push([p, m]) }
        return h.length || h.push([0, 0]), h }, TrimModifier.prototype.processShapes = function(t) { var e, r, s, i, a, n, o, h = this.shapes.length,
            l = this.sValue,
            p = this.eValue,
            m = 0; if (p === l)
            for (r = 0; h > r; r += 1) this.shapes[r].localShapeCollection.releaseShapes(), this.shapes[r].shape.mdf = !0, this.shapes[r].shape.paths = this.shapes[r].localShapeCollection;
        else if (!(1 === p && 0 === l || 0 === p && 1 === l)) { var f, c, d = []; for (r = 0; h > r; r += 1)
                if (f = this.shapes[r], f.shape.mdf || this.mdf || t || 2 === this.m) { if (e = f.shape.paths, i = e._length, o = 0, !f.shape.mdf && f.pathsData) o = f.totalShapeLength;
                    else { for (a = [], s = 0; i > s; s += 1) n = this.getSegmentsLength(e.shapes[s]), a.push(n), o += n.totalLength;
                        f.totalShapeLength = o, f.pathsData = a }
                    m += o, f.shape.mdf = !0 } else f.shape.paths = f.localShapeCollection;
            var s, i, u = l,
                y = p,
                g = 0; for (r = h - 1; r >= 0; r -= 1)
                if (f = this.shapes[r], f.shape.mdf) { if (c = f.localShapeCollection, c.releaseShapes(), 2 === this.m && h > 1) { var v = this.calculateShapeEdges(l, p, f.totalShapeLength, g, m);
                        g += f.totalShapeLength } else v = [
                        [u, y]
                    ]; for (i = v.length, s = 0; i > s; s += 1) { u = v[s][0], y = v[s][1], d.length = 0, 1 >= y ? d.push({ s: f.totalShapeLength * u, e: f.totalShapeLength * y }) : u >= 1 ? d.push({ s: f.totalShapeLength * (u - 1), e: f.totalShapeLength * (y - 1) }) : (d.push({ s: f.totalShapeLength * u, e: f.totalShapeLength }), d.push({ s: 0, e: f.totalShapeLength * (y - 1) })); var b = this.addShapes(f, d[0]); if (d[0].s !== d[0].e) { if (d.length > 1)
                                if (f.shape.v.c) { var E = b.pop();
                                    this.addPaths(b, c), b = this.addShapes(f, d[1], E) } else this.addPaths(b, c), b = this.addShapes(f, d[1]);
                            this.addPaths(b, c) } }
                    f.shape.paths = c } }
        this.dynamicProperties.length || (this.mdf = !1) }, TrimModifier.prototype.addPaths = function(t, e) { var r, s = t.length; for (r = 0; s > r; r += 1) e.addShape(t[r]) }, TrimModifier.prototype.addSegment = function(t, e, r, s, i, a, n) { i.setXYAt(e[0], e[1], "o", a), i.setXYAt(r[0], r[1], "i", a + 1), n && i.setXYAt(t[0], t[1], "v", a), i.setXYAt(s[0], s[1], "v", a + 1) }, TrimModifier.prototype.addShapes = function(t, e, r) { var s, i, a, n, o, h, l, p, m = t.pathsData,
            f = t.shape.paths.shapes,
            c = t.shape.paths._length,
            d = 0,
            u = [],
            y = !0; for (r ? (o = r._length, p = r._length) : (r = shape_pool.newShape(), o = 0, p = 0), u.push(r), s = 0; c > s; s += 1) { for (h = m[s].lengths, r.c = f[s].c, a = f[s].c ? h.length : h.length + 1, i = 1; a > i; i += 1)
                if (n = h[i - 1], d + n.addedLength < e.s) d += n.addedLength, r.c = !1;
                else { if (d > e.e) { r.c = !1; break }
                    e.s <= d && e.e >= d + n.addedLength ? (this.addSegment(f[s].v[i - 1], f[s].o[i - 1], f[s].i[i], f[s].v[i], r, o, y), y = !1) : (l = bez.getNewSegment(f[s].v[i - 1], f[s].v[i], f[s].o[i - 1], f[s].i[i], (e.s - d) / n.addedLength, (e.e - d) / n.addedLength, h[i - 1]), this.addSegment(l.pt1, l.pt3, l.pt4, l.pt2, r, o, y), y = !1, r.c = !1), d += n.addedLength, o += 1 }
            if (f[s].c) { if (n = h[i - 1], d <= e.e) { var g = h[i - 1].addedLength;
                    e.s <= d && e.e >= d + g ? (this.addSegment(f[s].v[i - 1], f[s].o[i - 1], f[s].i[0], f[s].v[0], r, o, y), y = !1) : (l = bez.getNewSegment(f[s].v[i - 1], f[s].v[0], f[s].o[i - 1], f[s].i[0], (e.s - d) / g, (e.e - d) / g, h[i - 1]), this.addSegment(l.pt1, l.pt3, l.pt4, l.pt2, r, o, y), y = !1, r.c = !1) } else r.c = !1;
                d += n.addedLength, o += 1 } if (r._length && (r.setXYAt(r.v[p][0], r.v[p][1], "i", p), r.setXYAt(r.v[r._length - 1][0], r.v[r._length - 1][1], "o", r._length - 1)), d > e.e) break;
            c - 1 > s && (r = shape_pool.newShape(), y = !0, u.push(r), o = 0) } return u }, ShapeModifiers.registerModifier("tm", TrimModifier), extendPrototype(ShapeModifier, RoundCornersModifier), RoundCornersModifier.prototype.processKeys = function(t) { if (this.elem.globalData.frameId !== this.frameId || t) { this.mdf = t ? !0 : !1, this.frameId = this.elem.globalData.frameId; var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(), this.dynamicProperties[e].mdf && (this.mdf = !0) } }, RoundCornersModifier.prototype.initModifierProperties = function(t, e) { this.getValue = this.processKeys, this.rd = PropertyFactory.getProp(t, e.r, 0, null, this.dynamicProperties), this.dynamicProperties.length || this.getValue(!0) }, RoundCornersModifier.prototype.processPath = function(t, e) { var r = shape_pool.newShape();
        r.c = t.c; var s, i, a, n, o, h, l, p, m, f, c, d, u, y = t._length,
            g = 0; for (s = 0; y > s; s += 1) i = t.v[s], n = t.o[s], a = t.i[s], i[0] === n[0] && i[1] === n[1] && i[0] === a[0] && i[1] === a[1] ? 0 !== s && s !== y - 1 || t.c ? (o = 0 === s ? t.v[y - 1] : t.v[s - 1], h = Math.sqrt(Math.pow(i[0] - o[0], 2) + Math.pow(i[1] - o[1], 2)), l = h ? Math.min(h / 2, e) / h : 0, p = d = i[0] + (o[0] - i[0]) * l, m = u = i[1] - (i[1] - o[1]) * l, f = p - (p - i[0]) * roundCorner, c = m - (m - i[1]) * roundCorner, r.setTripleAt(p, m, f, c, d, u, g), g += 1, o = s === y - 1 ? t.v[0] : t.v[s + 1], h = Math.sqrt(Math.pow(i[0] - o[0], 2) + Math.pow(i[1] - o[1], 2)), l = h ? Math.min(h / 2, e) / h : 0, p = f = i[0] + (o[0] - i[0]) * l, m = c = i[1] + (o[1] - i[1]) * l, d = p - (p - i[0]) * roundCorner, u = m - (m - i[1]) * roundCorner, r.setTripleAt(p, m, f, c, d, u, g), g += 1) : (r.setTripleAt(i[0], i[1], n[0], n[1], a[0], a[1], g), g += 1) : (r.setTripleAt(t.v[s][0], t.v[s][1], t.o[s][0], t.o[s][1], t.i[s][0], t.i[s][1], g), g += 1); return r }, RoundCornersModifier.prototype.processShapes = function(t) { var e, r, s, i, a = this.shapes.length,
            n = this.rd.v; if (0 !== n) { var o, h, l; for (r = 0; a > r; r += 1) { if (o = this.shapes[r], h = o.shape.paths, l = o.localShapeCollection, o.shape.mdf || this.mdf || t)
                    for (l.releaseShapes(), o.shape.mdf = !0, e = o.shape.paths.shapes, i = o.shape.paths._length, s = 0; i > s; s += 1) l.addShape(this.processPath(e[s], n));
                o.shape.paths = o.localShapeCollection } }
        this.dynamicProperties.length || (this.mdf = !1) }, ShapeModifiers.registerModifier("rd", RoundCornersModifier), extendPrototype(ShapeModifier, RepeaterModifier), RepeaterModifier.prototype.processKeys = function(t) { if (this.elem.globalData.frameId !== this.frameId || t) { this.mdf = t ? !0 : !1, this.frameId = this.elem.globalData.frameId; var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(), this.dynamicProperties[e].mdf && (this.mdf = !0) } }, RepeaterModifier.prototype.initModifierProperties = function(t, e) { this.getValue = this.processKeys, this.c = PropertyFactory.getProp(t, e.c, 0, null, this.dynamicProperties), this.o = PropertyFactory.getProp(t, e.o, 0, null, this.dynamicProperties), this.tr = PropertyFactory.getProp(t, e.tr, 2, null, this.dynamicProperties), this.dynamicProperties.length || this.getValue(!0), this.pMatrix = new Matrix, this.rMatrix = new Matrix, this.sMatrix = new Matrix, this.tMatrix = new Matrix, this.matrix = new Matrix }, RepeaterModifier.prototype.applyTransforms = function(t, e, r, s, i, a) { var n = a ? -1 : 1,
            o = s.s.v[0] + (1 - s.s.v[0]) * (1 - i),
            h = s.s.v[1] + (1 - s.s.v[1]) * (1 - i);
        t.translate(s.p.v[0] * n * i, s.p.v[1] * n * i, s.p.v[2]), e.translate(-s.a.v[0], -s.a.v[1], s.a.v[2]), e.rotate(-s.r.v * n * i), e.translate(s.a.v[0], s.a.v[1], s.a.v[2]), r.translate(-s.a.v[0], -s.a.v[1], s.a.v[2]), r.scale(a ? 1 / o : o, a ? 1 / h : h), r.translate(s.a.v[0], s.a.v[1], s.a.v[2]) }, RepeaterModifier.prototype.processShapes = function(t) { this.dynamicProperties.length || (this.mdf = !1); var e, r, s, i, a, n, o, h, l, p, m, f, c, d, u = this.shapes.length,
            y = Math.ceil(this.c.v),
            g = this.o.v,
            v = g % 1,
            b = g > 0 ? Math.floor(g) : Math.ceil(g),
            E = (this.tr.v.props, this.pMatrix.props),
            P = this.rMatrix.props,
            x = this.sMatrix.props,
            S = 0; for (e = 0; u > e; e += 1) { if (i = this.shapes[e], a = i.localShapeCollection, i.shape.mdf || this.mdf || t) { if (a.releaseShapes(), i.shape.mdf = !0, h = i.shape.paths, l = h.shapes, s = h._length, S = 0, this.pMatrix.reset(), this.rMatrix.reset(), this.sMatrix.reset(), this.tMatrix.reset(), this.matrix.reset(), g > 0) { for (; b > S;) this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, 1, !1), S += 1;
                    v && (this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, v, !1), S += v) } else if (0 > b) { for (; S > b;) this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, 1, !0), S -= 1;
                    v && (this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, -v, !0), S -= v) } for (r = 0; s > r; r += 1)
                    for (n = l[r], o = 0; y > o; o += 1) { if (0 !== o && this.applyTransforms(this.pMatrix, this.rMatrix, this.sMatrix, this.tr, 1, !1), i.data.transformers) { for (i.data.lvl = 0, d = 0, m = i.data.elements.length, p = 0; m > p; p += 1) d = Math.max(d, i.data.elements[p].st.lvl); for (c = i.data.transformers, m = c.length, p = m - 1; p >= d; p -= 1) f = c[p].mProps.v.props, this.matrix.transform(f[0], f[1], f[2], f[3], f[4], f[5], f[6], f[7], f[8], f[9], f[10], f[11], f[12], f[13], f[14], f[15]) }
                        0 !== S && (this.matrix.transform(P[0], P[1], P[2], P[3], P[4], P[5], P[6], P[7], P[8], P[9], P[10], P[11], P[12], P[13], P[14], P[15]), this.matrix.transform(x[0], x[1], x[2], x[3], x[4], x[5], x[6], x[7], x[8], x[9], x[10], x[11], x[12], x[13], x[14], x[15]), this.matrix.transform(E[0], E[1], E[2], E[3], E[4], E[5], E[6], E[7], E[8], E[9], E[10], E[11], E[12], E[13], E[14], E[15])), a.addShape(this.processPath(n, this.matrix)), this.matrix.reset(), S += 1 } }
            i.shape.paths = a } }, RepeaterModifier.prototype.processPath = function(t, e) { var r = shape_pool.clone(t, e); return r }, ShapeModifiers.registerModifier("rp", RepeaterModifier), ShapeCollection.prototype.addShape = function(t) { this._length === this._maxLength && (this.shapes = this.shapes.concat(Array.apply(null, { length: this._maxLength })), this._maxLength *= 2), this.shapes[this._length] = t, this._length += 1 }, ShapeCollection.prototype.releaseShapes = function() { var t; for (t = 0; t < this._length; t += 1) shape_pool.release(this.shapes[t]);
        this._length = 0 };
    var ImagePreloader = function() {
            function t() { this.loadedAssets += 1, this.loadedAssets === this.totalImages }

            function e(t) { var e = ""; if (this.assetsPath) { var r = t.p; - 1 !== r.indexOf("images/") && (r = r.split("/")[1]), e = this.assetsPath + r } else e = this.path, e += t.u ? t.u : "", e += t.p; return e }

            function r(e) { var r = document.createElement("img");
                r.addEventListener("load", t.bind(this), !1), r.addEventListener("error", t.bind(this), !1), r.src = e }

            function s(t) { this.totalAssets = t.length; var s; for (s = 0; s < this.totalAssets; s += 1) t[s].layers || (r.bind(this)(e.bind(this)(t[s])), this.totalImages += 1) }

            function i(t) { this.path = t || "" }

            function a(t) { this.assetsPath = t || "" } return function() { this.loadAssets = s, this.setAssetsPath = a, this.setPath = i, this.assetsPath = "", this.path = "", this.totalAssets = 0, this.totalImages = 0, this.loadedAssets = 0 } }(),
        featureSupport = function() { var t = { maskType: !0 }; return (/MSIE 10/i.test(navigator.userAgent) || /MSIE 9/i.test(navigator.userAgent) || /rv:11.0/i.test(navigator.userAgent) || /Edge\/\d./i.test(navigator.userAgent)) && (t.maskType = !1), t }(),
        filtersFactory = function() {
            function t(t) { var e = document.createElementNS(svgNS, "filter"); return e.setAttribute("id", t), e.setAttribute("filterUnits", "objectBoundingBox"), e.setAttribute("x", "0%"), e.setAttribute("y", "0%"), e.setAttribute("width", "100%"), e.setAttribute("height", "100%"), e }

            function e() { var t = document.createElementNS(svgNS, "feColorMatrix"); return t.setAttribute("type", "matrix"), t.setAttribute("color-interpolation-filters", "sRGB"), t.setAttribute("values", "0 0 0 1 0  0 0 0 1 0  0 0 0 1 0  0 0 0 0 1"), t } var r = {}; return r.createFilter = t, r.createAlphaToLuminanceFilter = e, r }(),
        pooling = function() {
            function t(t) { return t.concat(Array.apply(null, { length: t.length })) } return { "double": t } }(),
        point_pool = function() {
            function t() { var t; return s ? (s -= 1, t = a[s]) : t = [.1, .1], t }

            function e(t) { s === i && (a = pooling["double"](a), i = 2 * i), a[s] = t, s += 1 } var r = { newPoint: t, release: e },
                s = 0,
                i = 8,
                a = Array.apply(null, { length: i }); return r }(),
        shape_pool = function() {
            function t() { var t; return a ? (a -= 1, t = o[a]) : t = new ShapePath, t }

            function e(t) { a === n && (o = pooling["double"](o), n = 2 * n); var e, r = t._length; for (e = 0; r > e; e += 1) point_pool.release(t.v[e]), point_pool.release(t.i[e]), point_pool.release(t.o[e]);
                t._length = 0, t.c = !1, o[a] = t, a += 1 }

            function r(t, r) { for (; r--;) e(t[r]) }

            function s(e, r) { var s, i = e._length,
                    a = t();
                a._length = e._length, a.c = e.c; var n; for (s = 0; i > s; s += 1) r ? (n = r.applyToPointArray(e.v[s][0], e.v[s][1], 0, 2), a.setXYAt(n[0], n[1], "v", s), point_pool.release(n), n = r.applyToPointArray(e.o[s][0], e.o[s][1], 0, 2), a.setXYAt(n[0], n[1], "o", s), point_pool.release(n), n = r.applyToPointArray(e.i[s][0], e.i[s][1], 0, 2), a.setXYAt(n[0], n[1], "i", s), point_pool.release(n)) : a.setTripleAt(e.v[s][0], e.v[s][1], e.o[s][0], e.o[s][1], e.i[s][0], e.i[s][1], s); return a } var i = { clone: s, newShape: t, release: e, releaseArray: r },
                a = 0,
                n = 4,
                o = Array.apply(null, { length: n }); return i }(),
        shapeCollection_pool = function() {
            function t() { var t; return i ? (i -= 1, t = n[i]) : t = new ShapeCollection, t }

            function e(t) { var e, r = t._length; for (e = 0; r > e; e += 1) shape_pool.release(t.shapes[e]);
                t._length = 0, i === a && (n = pooling["double"](n), a = 2 * a), n[i] = t, i += 1 }

            function r(t, r) { e(t), i === a && (n = pooling["double"](n), a = 2 * a), n[i] = t, i += 1 } var s = { newShapeCollection: t, release: e, clone: r },
                i = 0,
                a = 4,
                n = Array.apply(null, { length: a }); return s }();
    BaseRenderer.prototype.checkLayers = function(t) { var e, r, s = this.layers.length; for (this.completeLayers = !0, e = s - 1; e >= 0; e--) this.elements[e] || (r = this.layers[e], r.ip - r.st <= t - this.layers[e].st && r.op - r.st > t - this.layers[e].st && this.buildItem(e)), this.completeLayers = this.elements[e] ? this.completeLayers : !1;
        this.checkPendingElements() }, BaseRenderer.prototype.createItem = function(t) { switch (t.ty) {
            case 2:
                return this.createImage(t);
            case 0:
                return this.createComp(t);
            case 1:
                return this.createSolid(t);
            case 4:
                return this.createShape(t);
            case 5:
                return this.createText(t);
            case 99:
                return null } return this.createBase(t) }, BaseRenderer.prototype.buildAllItems = function() { var t, e = this.layers.length; for (t = 0; e > t; t += 1) this.buildItem(t);
        this.checkPendingElements() }, BaseRenderer.prototype.includeLayers = function(t) { this.completeLayers = !1; var e, r, s = t.length,
            i = this.layers.length; for (e = 0; s > e; e += 1)
            for (r = 0; i > r;) { if (this.layers[r].id == t[e].id) { this.layers[r] = t[e]; break }
                r += 1 } }, BaseRenderer.prototype.setProjectInterface = function(t) { this.globalData.projectInterface = t }, BaseRenderer.prototype.initItems = function() { this.globalData.progressiveLoad || this.buildAllItems() }, BaseRenderer.prototype.buildElementParenting = function(t, e, r) { r = r || []; for (var s = this.elements, i = this.layers, a = 0, n = i.length; n > a;) i[a].ind == e && (s[a] && s[a] !== !0 ? void 0 !== i[a].parent ? (r.push(s[a]), s[a]._isParent = !0, this.buildElementParenting(t, i[a].parent, r)) : (r.push(s[a]), s[a]._isParent = !0, t.setHierarchy(r)) : (this.buildItem(a), this.addPendingElement(t))), a += 1 }, BaseRenderer.prototype.addPendingElement = function(t) { this.pendingElements.push(t) }, extendPrototype(BaseRenderer, SVGRenderer), SVGRenderer.prototype.createBase = function(t) { return new SVGBaseElement(t, this.layerElement, this.globalData, this) }, SVGRenderer.prototype.createShape = function(t) { return new IShapeElement(t, this.layerElement, this.globalData, this) }, SVGRenderer.prototype.createText = function(t) { return new SVGTextElement(t, this.layerElement, this.globalData, this) }, SVGRenderer.prototype.createImage = function(t) { return new IImageElement(t, this.layerElement, this.globalData, this) }, SVGRenderer.prototype.createComp = function(t) { return new ICompElement(t, this.layerElement, this.globalData, this) }, SVGRenderer.prototype.createSolid = function(t) { return new ISolidElement(t, this.layerElement, this.globalData, this) }, SVGRenderer.prototype.configAnimation = function(t) { this.layerElement = document.createElementNS(svgNS, "svg"), this.layerElement.setAttribute("xmlns", "http://www.w3.org/2000/svg"), this.layerElement.setAttribute("width", t.w), this.layerElement.setAttribute("height", t.h), this.layerElement.setAttribute("viewBox", "0 0 " + t.w + " " + t.h), this.layerElement.setAttribute("preserveAspectRatio", this.renderConfig.preserveAspectRatio), this.layerElement.style.width = "100%", this.layerElement.style.height = "100%", this.animationItem.wrapper.appendChild(this.layerElement); var e = document.createElementNS(svgNS, "defs");
        this.globalData.defs = e, this.layerElement.appendChild(e), this.globalData.getAssetData = this.animationItem.getAssetData.bind(this.animationItem), this.globalData.getAssetsPath = this.animationItem.getAssetsPath.bind(this.animationItem), this.globalData.progressiveLoad = this.renderConfig.progressiveLoad, this.globalData.frameId = 0, this.globalData.nm = t.nm, this.globalData.compSize = { w: t.w, h: t.h }, this.data = t, this.globalData.frameRate = t.fr; var r = document.createElementNS(svgNS, "clipPath"),
            s = document.createElementNS(svgNS, "rect");
        s.setAttribute("width", t.w), s.setAttribute("height", t.h), s.setAttribute("x", 0), s.setAttribute("y", 0); var i = "animationMask_" + randomString(10);
        r.setAttribute("id", i), r.appendChild(s); var a = document.createElementNS(svgNS, "g");
        a.setAttribute("clip-path", "url(#" + i + ")"), this.layerElement.appendChild(a), e.appendChild(r), this.layerElement = a, this.layers = t.layers, this.globalData.fontManager = new FontManager, this.globalData.fontManager.addChars(t.chars), this.globalData.fontManager.addFonts(t.fonts, e), this.elements = Array.apply(null, { length: t.layers.length }) }, SVGRenderer.prototype.destroy = function() { this.animationItem.wrapper.innerHTML = "", this.layerElement = null, this.globalData.defs = null; var t, e = this.layers ? this.layers.length : 0; for (t = 0; e > t; t++) this.elements[t] && this.elements[t].destroy();
        this.elements.length = 0, this.destroyed = !0, this.animationItem = null }, SVGRenderer.prototype.updateContainerSize = function() {}, SVGRenderer.prototype.buildItem = function(t) { var e = this.elements; if (!e[t] && 99 != this.layers[t].ty) { e[t] = !0; var r = this.createItem(this.layers[t]);
            e[t] = r, expressionsPlugin && (0 === this.layers[t].ty && this.globalData.projectInterface.registerComposition(r), r.initExpressions()), this.appendElementInPos(r, t), this.layers[t].tt && (this.elements[t - 1] && this.elements[t - 1] !== !0 ? r.setMatte(e[t - 1].layerId) : (this.buildItem(t - 1), this.addPendingElement(r))) } }, SVGRenderer.prototype.checkPendingElements = function() { for (; this.pendingElements.length;) { var t = this.pendingElements.pop(); if (t.checkParenting(), t.data.tt)
                for (var e = 0, r = this.elements.length; r > e;) { if (this.elements[e] === t) { t.setMatte(this.elements[e - 1].layerId); break }
                    e += 1 } } }, SVGRenderer.prototype.renderFrame = function(t) { if (this.renderedFrame != t && !this.destroyed) { null === t ? t = this.renderedFrame : this.renderedFrame = t, this.globalData.frameNum = t, this.globalData.frameId += 1, this.globalData.projectInterface.currentFrame = t; var e, r = this.layers.length; for (this.completeLayers || this.checkLayers(t), e = r - 1; e >= 0; e--)(this.completeLayers || this.elements[e]) && this.elements[e].prepareFrame(t - this.layers[e].st); for (e = r - 1; e >= 0; e--)(this.completeLayers || this.elements[e]) && this.elements[e].renderFrame() } }, SVGRenderer.prototype.appendElementInPos = function(t, e) { var r = t.getBaseElement(); if (r) { for (var s, i = 0; e > i;) this.elements[i] && this.elements[i] !== !0 && this.elements[i].getBaseElement() && (s = this.elements[i].getBaseElement()), i += 1;
            s ? this.layerElement.insertBefore(r, s) : this.layerElement.appendChild(r) } }, SVGRenderer.prototype.hide = function() { this.layerElement.style.display = "none" }, SVGRenderer.prototype.show = function() { this.layerElement.style.display = "block" }, SVGRenderer.prototype.searchExtraCompositions = function(t) { var e, r = t.length,
            s = document.createElementNS(svgNS, "g"); for (e = 0; r > e; e += 1)
            if (t[e].xt) { var i = this.createComp(t[e], s, this.globalData.comp, null);
                i.initExpressions(), this.globalData.projectInterface.registerComposition(i) } }, MaskElement.prototype.getMaskProperty = function(t) { return this.viewData[t].prop }, MaskElement.prototype.prepareFrame = function() { var t, e = this.dynamicProperties.length; for (t = 0; e > t; t += 1) this.dynamicProperties[t].getValue() }, MaskElement.prototype.renderFrame = function(t) { var e, r = this.masksProperties.length; for (e = 0; r > e; e++)
            if ((this.viewData[e].prop.mdf || this.firstFrame) && this.drawPath(this.masksProperties[e], this.viewData[e].prop.v, this.viewData[e]), (this.viewData[e].op.mdf || this.firstFrame) && this.viewData[e].elem.setAttribute("fill-opacity", this.viewData[e].op.v), "n" !== this.masksProperties[e].mode && (this.viewData[e].invRect && (this.element.finalTransform.mProp.mdf || this.firstFrame) && (this.viewData[e].invRect.setAttribute("x", -t.props[12]), this.viewData[e].invRect.setAttribute("y", -t.props[13])), this.storedData[e].x && (this.storedData[e].x.mdf || this.firstFrame))) { var s = this.storedData[e].expan;
                this.storedData[e].x.v < 0 ? ("erode" !== this.storedData[e].lastOperator && (this.storedData[e].lastOperator = "erode", this.storedData[e].elem.setAttribute("filter", "url(#" + this.storedData[e].filterId + ")")), s.setAttribute("radius", -this.storedData[e].x.v)) : ("dilate" !== this.storedData[e].lastOperator && (this.storedData[e].lastOperator = "dilate", this.storedData[e].elem.setAttribute("filter", null)), this.storedData[e].elem.setAttribute("stroke-width", 2 * this.storedData[e].x.v)) }
        this.firstFrame = !1 }, MaskElement.prototype.getMaskelement = function() { return this.maskElement }, MaskElement.prototype.createLayerSolidPath = function() { var t = "M0,0 "; return t += " h" + this.globalData.compSize.w, t += " v" + this.globalData.compSize.h, t += " h-" + this.globalData.compSize.w, t += " v-" + this.globalData.compSize.h + " " }, MaskElement.prototype.drawPath = function(t, e, r) { var s, i, a = " M" + e.v[0][0] + "," + e.v[0][1]; for (i = e._length, s = 1; i > s; s += 1) a += " C" + bm_rnd(e.o[s - 1][0]) + "," + bm_rnd(e.o[s - 1][1]) + " " + bm_rnd(e.i[s][0]) + "," + bm_rnd(e.i[s][1]) + " " + bm_rnd(e.v[s][0]) + "," + bm_rnd(e.v[s][1]);
        e.c && i > 1 && (a += " C" + bm_rnd(e.o[s - 1][0]) + "," + bm_rnd(e.o[s - 1][1]) + " " + bm_rnd(e.i[0][0]) + "," + bm_rnd(e.i[0][1]) + " " + bm_rnd(e.v[0][0]) + "," + bm_rnd(e.v[0][1])), r.lastPath !== a && (r.elem && (e.c ? t.inv ? r.elem.setAttribute("d", this.solidPath + a) : r.elem.setAttribute("d", a) : r.elem.setAttribute("d", "")), r.lastPath = a) }, MaskElement.prototype.getMask = function(t) { for (var e = 0, r = this.masksProperties.length; r > e;) { if (this.masksProperties[e].nm === t) return { maskPath: this.viewData[e].prop.pv };
            e += 1 } }, MaskElement.prototype.destroy = function() { this.element = null, this.globalData = null, this.maskElement = null, this.data = null, this.paths = null, this.masksProperties = null }, BaseElement.prototype.checkMasks = function() { if (!this.data.hasMask) return !1; for (var t = 0, e = this.data.masksProperties.length; e > t;) { if ("n" !== this.data.masksProperties[t].mode && this.data.masksProperties[t].cl !== !1) return !0;
            t += 1 } return !1 }, BaseElement.prototype.checkParenting = function() { void 0 !== this.data.parent && this.comp.buildElementParenting(this, this.data.parent) }, BaseElement.prototype.prepareFrame = function(t) { this.data.ip - this.data.st <= t && this.data.op - this.data.st > t ? this.isVisible !== !0 && (this.elemMdf = !0, this.globalData.mdf = !0, this.isVisible = !0, this.firstFrame = !0, this.data.hasMask && (this.maskManager.firstFrame = !0)) : this.isVisible !== !1 && (this.elemMdf = !0, this.globalData.mdf = !0, this.isVisible = !1); var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1)(this.isVisible || this._isParent && "transform" === this.dynamicProperties[e].type) && (this.dynamicProperties[e].getValue(), this.dynamicProperties[e].mdf && (this.elemMdf = !0, this.globalData.mdf = !0)); return this.data.hasMask && this.isVisible && this.maskManager.prepareFrame(t * this.data.sr), this.currentFrameNum = t * this.data.sr, this.isVisible }, BaseElement.prototype.globalToLocal = function(t) { var e = [];
        e.push(this.finalTransform); for (var r = !0, s = this.comp; r;) s.finalTransform ? (s.data.hasMask && e.splice(0, 0, s.finalTransform), s = s.comp) : r = !1; var i, a, n = e.length; for (i = 0; n > i; i += 1) a = e[i].mat.applyToPointArray(0, 0, 0), t = [t[0] - a[0], t[1] - a[1], 0]; return t }, BaseElement.prototype.initExpressions = function() { this.layerInterface = LayerExpressionInterface(this), this.data.hasMask && this.layerInterface.registerMaskInterface(this.maskManager); var t = EffectsExpressionInterface.createEffectsInterface(this, this.layerInterface);
        this.layerInterface.registerEffectsInterface(t), 0 === this.data.ty || this.data.xt ? this.compInterface = CompExpressionInterface(this) : 4 === this.data.ty ? this.layerInterface.shapeInterface = ShapeExpressionInterface.createShapeInterface(this.shapesData, this.viewData, this.layerInterface) : 5 === this.data.ty && (this.layerInterface.textInterface = TextExpressionInterface(this)) }, BaseElement.prototype.setBlendMode = function() { var t = ""; switch (this.data.bm) {
            case 1:
                t = "multiply"; break;
            case 2:
                t = "screen"; break;
            case 3:
                t = "overlay"; break;
            case 4:
                t = "darken"; break;
            case 5:
                t = "lighten"; break;
            case 6:
                t = "color-dodge"; break;
            case 7:
                t = "color-burn"; break;
            case 8:
                t = "hard-light"; break;
            case 9:
                t = "soft-light"; break;
            case 10:
                t = "difference"; break;
            case 11:
                t = "exclusion"; break;
            case 12:
                t = "hue"; break;
            case 13:
                t = "saturation"; break;
            case 14:
                t = "color"; break;
            case 15:
                t = "luminosity" } var e = this.baseElement || this.layerElement;
        e.style["mix-blend-mode"] = t }, BaseElement.prototype.init = function() { this.data.sr || (this.data.sr = 1), this.dynamicProperties = [], this.data.ef && (this.effects = new EffectsManager(this.data, this, this.dynamicProperties)), this.hidden = !1, this.firstFrame = !0, this.isVisible = !1, this._isParent = !1, this.currentFrameNum = -99999, this.lastNum = -99999, this.data.ks && (this.finalTransform = { mProp: PropertyFactory.getProp(this, this.data.ks, 2, null, this.dynamicProperties), matMdf: !1, opMdf: !1, mat: new Matrix, opacity: 1 }, this.data.ao && (this.finalTransform.mProp.autoOriented = !0), this.finalTransform.op = this.finalTransform.mProp.o, this.transform = this.finalTransform.mProp, 11 !== this.data.ty && this.createElements(), this.data.hasMask && this.addMasks(this.data)), this.elemMdf = !1 }, BaseElement.prototype.getType = function() { return this.type }, BaseElement.prototype.resetHierarchy = function() { this.hierarchy ? this.hierarchy.length = 0 : this.hierarchy = [] }, BaseElement.prototype.getHierarchy = function() { return this.hierarchy || (this.hierarchy = []), this.hierarchy }, BaseElement.prototype.setHierarchy = function(t) { this.hierarchy = t }, BaseElement.prototype.getLayerSize = function() {
        return 5 === this.data.ty ? {
            w: this.data.textData.width,
            h: this.data.textData.height
        } : { w: this.data.width, h: this.data.height }
    }, BaseElement.prototype.hide = function() {}, BaseElement.prototype.mHelper = new Matrix, createElement(BaseElement, SVGBaseElement), SVGBaseElement.prototype.createElements = function() { this.layerElement = document.createElementNS(svgNS, "g"), this.transformedElement = this.layerElement, this.data.hasMask && (this.maskedElement = this.layerElement); var t = null; if (this.data.td) { if (3 == this.data.td || 1 == this.data.td) { var e = document.createElementNS(svgNS, "mask"); if (e.setAttribute("id", this.layerId), e.setAttribute("mask-type", 3 == this.data.td ? "luminance" : "alpha"), e.appendChild(this.layerElement), t = e, this.globalData.defs.appendChild(e), !featureSupport.maskType && 1 == this.data.td) { e.setAttribute("mask-type", "luminance"); var r = randomString(10),
                        s = filtersFactory.createFilter(r);
                    this.globalData.defs.appendChild(s), s.appendChild(filtersFactory.createAlphaToLuminanceFilter()); var i = document.createElementNS(svgNS, "g");
                    i.appendChild(this.layerElement), t = i, e.appendChild(i), i.setAttribute("filter", "url(#" + r + ")") } } else if (2 == this.data.td) { var a = document.createElementNS(svgNS, "mask");
                a.setAttribute("id", this.layerId), a.setAttribute("mask-type", "alpha"); var n = document.createElementNS(svgNS, "g");
                a.appendChild(n); var r = randomString(10),
                    s = filtersFactory.createFilter(r),
                    o = document.createElementNS(svgNS, "feColorMatrix");
                o.setAttribute("type", "matrix"), o.setAttribute("color-interpolation-filters", "sRGB"), o.setAttribute("values", "1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 -1 1"), s.appendChild(o), this.globalData.defs.appendChild(s); var h = document.createElementNS(svgNS, "rect"); if (h.setAttribute("width", this.comp.data.w), h.setAttribute("height", this.comp.data.h), h.setAttribute("x", "0"), h.setAttribute("y", "0"), h.setAttribute("fill", "#ffffff"), h.setAttribute("opacity", "0"), n.setAttribute("filter", "url(#" + r + ")"), n.appendChild(h), n.appendChild(this.layerElement), t = n, !featureSupport.maskType) { a.setAttribute("mask-type", "luminance"), s.appendChild(filtersFactory.createAlphaToLuminanceFilter()); var i = document.createElementNS(svgNS, "g");
                    n.appendChild(h), i.appendChild(this.layerElement), t = i, n.appendChild(i) }
                this.globalData.defs.appendChild(a) } } else(this.data.hasMask || this.data.tt) && this.data.tt ? (this.matteElement = document.createElementNS(svgNS, "g"), this.matteElement.appendChild(this.layerElement), t = this.matteElement, this.baseElement = this.matteElement) : this.baseElement = this.layerElement; if (!this.data.ln && !this.data.cl || 4 !== this.data.ty && 0 !== this.data.ty || (this.data.ln && this.layerElement.setAttribute("id", this.data.ln), this.data.cl && this.layerElement.setAttribute("class", this.data.cl)), 0 === this.data.ty) { var l = document.createElementNS(svgNS, "clipPath"),
                p = document.createElementNS(svgNS, "path");
            p.setAttribute("d", "M0,0 L" + this.data.w + ",0 L" + this.data.w + "," + this.data.h + " L0," + this.data.h + "z"); var m = "cp_" + randomString(8); if (l.setAttribute("id", m), l.appendChild(p), this.globalData.defs.appendChild(l), this.checkMasks()) { var f = document.createElementNS(svgNS, "g");
                f.setAttribute("clip-path", "url(#" + m + ")"), f.appendChild(this.layerElement), this.transformedElement = f, t ? t.appendChild(this.transformedElement) : this.baseElement = this.transformedElement } else this.layerElement.setAttribute("clip-path", "url(#" + m + ")") }
        0 !== this.data.bm && this.setBlendMode(), this.layerElement !== this.parentContainer && (this.placeholder = null), this.data.ef && (this.effectsManager = new SVGEffects(this)), this.checkParenting() }, SVGBaseElement.prototype.setBlendMode = BaseElement.prototype.setBlendMode, SVGBaseElement.prototype.renderFrame = function(t) { if (3 === this.data.ty || this.data.hd || !this.isVisible) return !1;
        this.lastNum = this.currentFrameNum, this.finalTransform.opMdf = this.firstFrame || this.finalTransform.op.mdf, this.finalTransform.matMdf = this.firstFrame || this.finalTransform.mProp.mdf, this.finalTransform.opacity = this.finalTransform.op.v; var e, r = this.finalTransform.mat; if (this.hierarchy) { var s = 0,
                i = this.hierarchy.length; if (!this.finalTransform.matMdf)
                for (; i > s;) { if (this.hierarchy[s].finalTransform.mProp.mdf) { this.finalTransform.matMdf = !0; break }
                    s += 1 }
            if (this.finalTransform.matMdf)
                for (e = this.finalTransform.mProp.v.props, r.cloneFromProps(e), s = 0; i > s; s += 1) e = this.hierarchy[s].finalTransform.mProp.v.props, r.transform(e[0], e[1], e[2], e[3], e[4], e[5], e[6], e[7], e[8], e[9], e[10], e[11], e[12], e[13], e[14], e[15]) } else this.isVisible && (r = this.finalTransform.mProp.v); return this.finalTransform.matMdf && this.layerElement && this.transformedElement.setAttribute("transform", r.to2dCSS()), this.finalTransform.opMdf && this.layerElement && this.transformedElement.setAttribute("opacity", this.finalTransform.op.v), this.data.hasMask && this.maskManager.renderFrame(r), this.effectsManager && this.effectsManager.renderFrame(this.firstFrame), this.isVisible }, SVGBaseElement.prototype.destroy = function() { this.layerElement = null, this.parentContainer = null, this.matteElement && (this.matteElement = null), this.maskManager && this.maskManager.destroy() }, SVGBaseElement.prototype.getBaseElement = function() { return this.baseElement }, SVGBaseElement.prototype.addMasks = function(t) { this.maskManager = new MaskElement(t, this, this.globalData) }, SVGBaseElement.prototype.setMatte = function(t) { this.matteElement && this.matteElement.setAttribute("mask", "url(#" + t + ")") }, SVGBaseElement.prototype.setMatte = function(t) { this.matteElement && this.matteElement.setAttribute("mask", "url(#" + t + ")") }, SVGBaseElement.prototype.hide = function() {}, ITextElement.prototype.init = function() { this._parent.init.call(this), this.lettersChangedFlag = !1, this.currentTextDocumentData = {}; var t = this.data;
        this.viewData = { m: { a: PropertyFactory.getProp(this, t.t.m.a, 1, 0, this.dynamicProperties) } }; var e = this.data.t; if (e.a.length) { this.viewData.a = Array.apply(null, { length: e.a.length }); var r, s, i, a = e.a.length; for (r = 0; a > r; r += 1) i = e.a[r], s = { a: {}, s: {} }, "r" in i.a && (s.a.r = PropertyFactory.getProp(this, i.a.r, 0, degToRads, this.dynamicProperties)), "rx" in i.a && (s.a.rx = PropertyFactory.getProp(this, i.a.rx, 0, degToRads, this.dynamicProperties)), "ry" in i.a && (s.a.ry = PropertyFactory.getProp(this, i.a.ry, 0, degToRads, this.dynamicProperties)), "sk" in i.a && (s.a.sk = PropertyFactory.getProp(this, i.a.sk, 0, degToRads, this.dynamicProperties)), "sa" in i.a && (s.a.sa = PropertyFactory.getProp(this, i.a.sa, 0, degToRads, this.dynamicProperties)), "s" in i.a && (s.a.s = PropertyFactory.getProp(this, i.a.s, 1, .01, this.dynamicProperties)), "a" in i.a && (s.a.a = PropertyFactory.getProp(this, i.a.a, 1, 0, this.dynamicProperties)), "o" in i.a && (s.a.o = PropertyFactory.getProp(this, i.a.o, 0, .01, this.dynamicProperties)), "p" in i.a && (s.a.p = PropertyFactory.getProp(this, i.a.p, 1, 0, this.dynamicProperties)), "sw" in i.a && (s.a.sw = PropertyFactory.getProp(this, i.a.sw, 0, 0, this.dynamicProperties)), "sc" in i.a && (s.a.sc = PropertyFactory.getProp(this, i.a.sc, 1, 0, this.dynamicProperties)), "fc" in i.a && (s.a.fc = PropertyFactory.getProp(this, i.a.fc, 1, 0, this.dynamicProperties)), "fh" in i.a && (s.a.fh = PropertyFactory.getProp(this, i.a.fh, 0, 0, this.dynamicProperties)), "fs" in i.a && (s.a.fs = PropertyFactory.getProp(this, i.a.fs, 0, .01, this.dynamicProperties)), "fb" in i.a && (s.a.fb = PropertyFactory.getProp(this, i.a.fb, 0, .01, this.dynamicProperties)), "t" in i.a && (s.a.t = PropertyFactory.getProp(this, i.a.t, 0, 0, this.dynamicProperties)), s.s = PropertyFactory.getTextSelectorProp(this, i.s, this.dynamicProperties), s.s.t = i.s.t, this.viewData.a[r] = s } else this.viewData.a = [];
        e.p && "m" in e.p ? (this.viewData.p = { f: PropertyFactory.getProp(this, e.p.f, 0, 0, this.dynamicProperties), l: PropertyFactory.getProp(this, e.p.l, 0, 0, this.dynamicProperties), r: e.p.r, m: this.maskManager.getMaskProperty(e.p.m) }, this.maskPath = !0) : this.maskPath = !1 }, ITextElement.prototype.prepareFrame = function(t) { var e = 0,
            r = this.data.t.d.k.length,
            s = this.data.t.d.k[e].s; for (e += 1; r > e && !(this.data.t.d.k[e].t > t);) s = this.data.t.d.k[e].s, e += 1;
        this.lettersChangedFlag = !1, s !== this.currentTextDocumentData && (this.currentTextDocumentData = s, this.lettersChangedFlag = !0, this.buildNewText()), this._parent.prepareFrame.call(this, t) }, ITextElement.prototype.createPathShape = function(t, e) { var r, s, i, a, n = e.length,
            o = ""; for (r = 0; n > r; r += 1) { for (i = e[r].ks.k.i.length, a = e[r].ks.k, s = 1; i > s; s += 1) 1 == s && (o += " M" + t.applyToPointStringified(a.v[0][0], a.v[0][1])), o += " C" + t.applyToPointStringified(a.o[s - 1][0], a.o[s - 1][1]) + " " + t.applyToPointStringified(a.i[s][0], a.i[s][1]) + " " + t.applyToPointStringified(a.v[s][0], a.v[s][1]);
            o += " C" + t.applyToPointStringified(a.o[s - 1][0], a.o[s - 1][1]) + " " + t.applyToPointStringified(a.i[0][0], a.i[0][1]) + " " + t.applyToPointStringified(a.v[0][0], a.v[0][1]), o += "z" } return o }, ITextElement.prototype.getMeasures = function() { var t, e, r, s, i = this.mHelper,
            a = this.renderType,
            n = this.data,
            o = this.currentTextDocumentData,
            h = o.l; if (this.maskPath) { var l = this.viewData.p.m; if (!this.viewData.p.n || this.viewData.p.mdf) { var p = l.v;
                this.viewData.p.r && (p = reversePath(p)); var m = { tLength: 0, segments: [] };
                s = p.v.length - 1; var f, c = 0; for (r = 0; s > r; r += 1) f = { s: p.v[r], e: p.v[r + 1], to: [p.o[r][0] - p.v[r][0], p.o[r][1] - p.v[r][1]], ti: [p.i[r + 1][0] - p.v[r + 1][0], p.i[r + 1][1] - p.v[r + 1][1]] }, bez.buildBezierData(f), m.tLength += f.bezierData.segmentLength, m.segments.push(f), c += f.bezierData.segmentLength;
                r = s, l.v.c && (f = { s: p.v[r], e: p.v[0], to: [p.o[r][0] - p.v[r][0], p.o[r][1] - p.v[r][1]], ti: [p.i[0][0] - p.v[0][0], p.i[0][1] - p.v[0][1]] }, bez.buildBezierData(f), m.tLength += f.bezierData.segmentLength, m.segments.push(f), c += f.bezierData.segmentLength), this.viewData.p.pi = m } var d, u, y, m = this.viewData.p.pi,
                g = this.viewData.p.f.v,
                v = 0,
                b = 1,
                E = 0,
                P = !0,
                x = m.segments; if (0 > g && l.v.c)
                for (m.tLength < Math.abs(g) && (g = -Math.abs(g) % m.tLength), v = x.length - 1, y = x[v].bezierData.points, b = y.length - 1; 0 > g;) g += y[b].partialLength, b -= 1, 0 > b && (v -= 1, y = x[v].bezierData.points, b = y.length - 1);
            y = x[v].bezierData.points, u = y[b - 1], d = y[b]; var S, C, k = d.partialLength }
        s = h.length, t = 0, e = 0; var M, A, D, T, w, F = 1.2 * o.s * .714,
            I = !0,
            _ = this.viewData,
            V = Array.apply(null, { length: s });
        T = _.a.length; var R, B, N, L, G, O, j, H, z, W, X, Y, q, U, Z, J, K = -1,
            Q = g,
            $ = v,
            tt = b,
            et = -1,
            rt = 0; for (r = 0; s > r; r += 1)
            if (i.reset(), O = 1, h[r].n) t = 0, e += o.yOffset, e += I ? 1 : 0, g = Q, I = !1, rt = 0, this.maskPath && (v = $, b = tt, y = x[v].bezierData.points, u = y[b - 1], d = y[b], k = d.partialLength, E = 0), V[r] = this.emptyProp;
            else { if (this.maskPath) { if (et !== h[r].line) { switch (o.j) {
                            case 1:
                                g += c - o.lineWidths[h[r].line]; break;
                            case 2:
                                g += (c - o.lineWidths[h[r].line]) / 2 }
                        et = h[r].line }
                    K !== h[r].ind && (h[K] && (g += h[K].extra), g += h[r].an / 2, K = h[r].ind), g += _.m.a.v[0] * h[r].an / 200; var st = 0; for (D = 0; T > D; D += 1) M = _.a[D].a, "p" in M && (A = _.a[D].s, B = A.getMult(h[r].anIndexes[D], n.t.a[D].s.totalChars), st += B.length ? M.p.v[0] * B[0] : M.p.v[0] * B); for (P = !0; P;) E + k >= g + st || !y ? (S = (g + st - E) / d.partialLength, L = u.point[0] + (d.point[0] - u.point[0]) * S, G = u.point[1] + (d.point[1] - u.point[1]) * S, i.translate(0, -(_.m.a.v[1] * F / 100) + e), P = !1) : y && (E += d.partialLength, b += 1, b >= y.length && (b = 0, v += 1, x[v] ? y = x[v].bezierData.points : l.v.c ? (b = 0, v = 0, y = x[v].bezierData.points) : (E -= d.partialLength, y = null)), y && (u = d, d = y[b], k = d.partialLength));
                    N = h[r].an / 2 - h[r].add, i.translate(-N, 0, 0) } else N = h[r].an / 2 - h[r].add, i.translate(-N, 0, 0), i.translate(-_.m.a.v[0] * h[r].an / 200, -_.m.a.v[1] * F / 100, 0); for (rt += h[r].l / 2, D = 0; T > D; D += 1) M = _.a[D].a, "t" in M && (A = _.a[D].s, B = A.getMult(h[r].anIndexes[D], n.t.a[D].s.totalChars), this.maskPath ? g += B.length ? M.t * B[0] : M.t * B : t += B.length ? M.t.v * B[0] : M.t.v * B); for (rt += h[r].l / 2, o.strokeWidthAnim && (H = o.sw || 0), o.strokeColorAnim && (j = o.sc ? [o.sc[0], o.sc[1], o.sc[2]] : [0, 0, 0]), o.fillColorAnim && (z = [o.fc[0], o.fc[1], o.fc[2]]), D = 0; T > D; D += 1) M = _.a[D].a, "a" in M && (A = _.a[D].s, B = A.getMult(h[r].anIndexes[D], n.t.a[D].s.totalChars), B.length ? i.translate(-M.a.v[0] * B[0], -M.a.v[1] * B[1], M.a.v[2] * B[2]) : i.translate(-M.a.v[0] * B, -M.a.v[1] * B, M.a.v[2] * B)); for (D = 0; T > D; D += 1) M = _.a[D].a, "s" in M && (A = _.a[D].s, B = A.getMult(h[r].anIndexes[D], n.t.a[D].s.totalChars), B.length ? i.scale(1 + (M.s.v[0] - 1) * B[0], 1 + (M.s.v[1] - 1) * B[1], 1) : i.scale(1 + (M.s.v[0] - 1) * B, 1 + (M.s.v[1] - 1) * B, 1)); for (D = 0; T > D; D += 1) { if (M = _.a[D].a, A = _.a[D].s, B = A.getMult(h[r].anIndexes[D], n.t.a[D].s.totalChars), "sk" in M && (B.length ? i.skewFromAxis(-M.sk.v * B[0], M.sa.v * B[1]) : i.skewFromAxis(-M.sk.v * B, M.sa.v * B)), "r" in M && i.rotateZ(B.length ? -M.r.v * B[2] : -M.r.v * B), "ry" in M && i.rotateY(B.length ? M.ry.v * B[1] : M.ry.v * B), "rx" in M && i.rotateX(B.length ? M.rx.v * B[0] : M.rx.v * B), "o" in M && (O += B.length ? (M.o.v * B[0] - O) * B[0] : (M.o.v * B - O) * B), o.strokeWidthAnim && "sw" in M && (H += B.length ? M.sw.v * B[0] : M.sw.v * B), o.strokeColorAnim && "sc" in M)
                        for (W = 0; 3 > W; W += 1) j[W] = Math.round(B.length ? 255 * (j[W] + (M.sc.v[W] - j[W]) * B[0]) : 255 * (j[W] + (M.sc.v[W] - j[W]) * B)); if (o.fillColorAnim) { if ("fc" in M)
                            for (W = 0; 3 > W; W += 1) z[W] = B.length ? z[W] + (M.fc.v[W] - z[W]) * B[0] : z[W] + (M.fc.v[W] - z[W]) * B; "fh" in M && (z = B.length ? addHueToRGB(z, M.fh.v * B[0]) : addHueToRGB(z, M.fh.v * B)), "fs" in M && (z = B.length ? addSaturationToRGB(z, M.fs.v * B[0]) : addSaturationToRGB(z, M.fs.v * B)), "fb" in M && (z = B.length ? addBrightnessToRGB(z, M.fb.v * B[0]) : addBrightnessToRGB(z, M.fb.v * B)) } } for (D = 0; T > D; D += 1) M = _.a[D].a, "p" in M && (A = _.a[D].s, B = A.getMult(h[r].anIndexes[D], n.t.a[D].s.totalChars), this.maskPath ? B.length ? i.translate(0, M.p.v[1] * B[0], -M.p.v[2] * B[1]) : i.translate(0, M.p.v[1] * B, -M.p.v[2] * B) : B.length ? i.translate(M.p.v[0] * B[0], M.p.v[1] * B[1], -M.p.v[2] * B[2]) : i.translate(M.p.v[0] * B, M.p.v[1] * B, -M.p.v[2] * B)); if (o.strokeWidthAnim && (X = 0 > H ? 0 : H), o.strokeColorAnim && (Y = "rgb(" + Math.round(255 * j[0]) + "," + Math.round(255 * j[1]) + "," + Math.round(255 * j[2]) + ")"), o.fillColorAnim && (q = "rgb(" + Math.round(255 * z[0]) + "," + Math.round(255 * z[1]) + "," + Math.round(255 * z[2]) + ")"), this.maskPath) { if (n.t.p.p) { C = (d.point[1] - u.point[1]) / (d.point[0] - u.point[0]); var it = 180 * Math.atan(C) / Math.PI;
                        d.point[0] < u.point[0] && (it += 180), i.rotate(-it * Math.PI / 180) }
                    i.translate(L, G, 0), i.translate(_.m.a.v[0] * h[r].an / 200, _.m.a.v[1] * F / 100, 0), g -= _.m.a.v[0] * h[r].an / 200, h[r + 1] && K !== h[r + 1].ind && (g += h[r].an / 2, g += o.tr / 1e3 * o.s) } else { switch (i.translate(t, e, 0), o.ps && i.translate(o.ps[0], o.ps[1] + o.ascent, 0), o.j) {
                        case 1:
                            i.translate(o.justifyOffset + (o.boxWidth - o.lineWidths[h[r].line]), 0, 0); break;
                        case 2:
                            i.translate(o.justifyOffset + (o.boxWidth - o.lineWidths[h[r].line]) / 2, 0, 0) }
                    i.translate(N, 0, 0), i.translate(_.m.a.v[0] * h[r].an / 200, _.m.a.v[1] * F / 100, 0), t += h[r].l + o.tr / 1e3 * o.s } "html" === a ? U = i.toCSS() : "svg" === a ? U = i.to2dCSS() : Z = [i.props[0], i.props[1], i.props[2], i.props[3], i.props[4], i.props[5], i.props[6], i.props[7], i.props[8], i.props[9], i.props[10], i.props[11], i.props[12], i.props[13], i.props[14], i.props[15]], J = O, R = this.renderedLetters[r], !R || R.o === J && R.sw === X && R.sc === Y && R.fc === q ? "svg" !== a && "html" !== a || R && R.m === U ? "canvas" !== a || R && R.props[0] === Z[0] && R.props[1] === Z[1] && R.props[4] === Z[4] && R.props[5] === Z[5] && R.props[12] === Z[12] && R.props[13] === Z[13] ? w = R : (this.lettersChangedFlag = !0, w = new LetterProps(J, X, Y, q, null, Z)) : (this.lettersChangedFlag = !0, w = new LetterProps(J, X, Y, q, U)) : (this.lettersChangedFlag = !0, w = new LetterProps(J, X, Y, q, U, Z)), this.renderedLetters[r] = w } }, ITextElement.prototype.emptyProp = new LetterProps, createElement(SVGBaseElement, SVGTextElement), SVGTextElement.prototype.init = ITextElement.prototype.init, SVGTextElement.prototype.createPathShape = ITextElement.prototype.createPathShape, SVGTextElement.prototype.getMeasures = ITextElement.prototype.getMeasures, SVGTextElement.prototype.prepareFrame = ITextElement.prototype.prepareFrame, SVGTextElement.prototype.createElements = function() { this._parent.createElements.call(this), this.data.ln && this.layerElement.setAttribute("id", this.data.ln), this.data.cl && this.layerElement.setAttribute("class", this.data.cl) }, SVGTextElement.prototype.buildNewText = function() { var t, e, r = this.currentTextDocumentData;
        this.renderedLetters = Array.apply(null, { length: this.currentTextDocumentData.l ? this.currentTextDocumentData.l.length : 0 }), r.fc ? this.layerElement.setAttribute("fill", "rgb(" + Math.round(255 * r.fc[0]) + "," + Math.round(255 * r.fc[1]) + "," + Math.round(255 * r.fc[2]) + ")") : this.layerElement.setAttribute("fill", "rgba(0,0,0,0)"), r.sc && (this.layerElement.setAttribute("stroke", "rgb(" + Math.round(255 * r.sc[0]) + "," + Math.round(255 * r.sc[1]) + "," + Math.round(255 * r.sc[2]) + ")"), this.layerElement.setAttribute("stroke-width", r.sw)), this.layerElement.setAttribute("font-size", r.s); var s = this.globalData.fontManager.getFontByName(r.f); if (s.fClass) this.layerElement.setAttribute("class", s.fClass);
        else { this.layerElement.setAttribute("font-family", s.fFamily); var i = r.fWeight,
                a = r.fStyle;
            this.layerElement.setAttribute("font-style", a), this.layerElement.setAttribute("font-weight", i) } var n = r.l || []; if (e = n.length) { var o, h, l = this.mHelper,
                p = "",
                m = this.data.singleShape; if (m) var f = 0,
                c = 0,
                d = r.lineWidths,
                u = r.boxWidth,
                y = !0; var g = 0; for (t = 0; e > t; t += 1) { if (this.globalData.fontManager.chars ? m && 0 !== t || (o = this.textSpans[g] ? this.textSpans[g] : document.createElementNS(svgNS, "path")) : o = this.textSpans[g] ? this.textSpans[g] : document.createElementNS(svgNS, "text"), o.style.display = "inherit", o.setAttribute("stroke-linecap", "butt"), o.setAttribute("stroke-linejoin", "round"), o.setAttribute("stroke-miterlimit", "4"), m && n[t].n && (f = 0, c += r.yOffset, c += y ? 1 : 0, y = !1), l.reset(), this.globalData.fontManager.chars && l.scale(r.s / 100, r.s / 100), m) { switch (r.ps && l.translate(r.ps[0], r.ps[1] + r.ascent, 0), r.j) {
                        case 1:
                            l.translate(r.justifyOffset + (u - d[n[t].line]), 0, 0); break;
                        case 2:
                            l.translate(r.justifyOffset + (u - d[n[t].line]) / 2, 0, 0) }
                    l.translate(f, c, 0) } if (this.globalData.fontManager.chars) { var v, b = this.globalData.fontManager.getCharData(r.t.charAt(t), s.fStyle, this.globalData.fontManager.getFontByName(r.f).fFamily);
                    v = b ? b.data : null, v && v.shapes && (h = v.shapes[0].it, m || (p = ""), p += this.createPathShape(l, h), m || o.setAttribute("d", p)), m || this.layerElement.appendChild(o) } else o.textContent = n[t].val, o.setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.layerElement.appendChild(o), m && o.setAttribute("transform", l.to2dCSS());
                m && (f += n[t].l, f += r.tr / 1e3 * r.s), this.textSpans[g] = o, g += 1 } if (!m)
                for (; g < this.textSpans.length;) this.textSpans[g].style.display = "none", g += 1;
            m && this.globalData.fontManager.chars && (o.setAttribute("d", p), this.layerElement.appendChild(o)) } }, SVGTextElement.prototype.hide = function() { this.hidden || (this.layerElement.style.display = "none", this.hidden = !0) }, SVGTextElement.prototype.renderFrame = function(t) { var e = this._parent.renderFrame.call(this, t); if (e === !1) return void this.hide(); if (this.hidden && (this.hidden = !1, this.layerElement.style.display = "block"), !this.data.singleShape && (this.getMeasures(), this.lettersChangedFlag)) { var r, s, i = this.renderedLetters,
                a = this.currentTextDocumentData.l;
            s = a.length; var n; for (r = 0; s > r; r += 1) a[r].n || (n = i[r], this.textSpans[r].setAttribute("transform", n.m), this.textSpans[r].setAttribute("opacity", n.o), n.sw && this.textSpans[r].setAttribute("stroke-width", n.sw), n.sc && this.textSpans[r].setAttribute("stroke", n.sc), n.fc && this.textSpans[r].setAttribute("fill", n.fc));
            this.firstFrame && (this.firstFrame = !1) } }, SVGTextElement.prototype.destroy = function() { this._parent.destroy.call(this._parent) }, SVGTintFilter.prototype.renderFrame = function(t) { if (t || this.filterManager.mdf) { var e = this.filterManager.effectElements[0].p.v,
                r = this.filterManager.effectElements[1].p.v,
                s = this.filterManager.effectElements[2].p.v / 100;
            this.matrixFilter.setAttribute("values", r[0] - e[0] + " 0 0 0 " + e[0] + " " + (r[1] - e[1]) + " 0 0 0 " + e[1] + " " + (r[2] - e[2]) + " 0 0 0 " + e[2] + " 0 0 0 " + s + " 0") } }, SVGFillFilter.prototype.renderFrame = function(t) { if (t || this.filterManager.mdf) { var e = this.filterManager.effectElements[2].p.v,
                r = this.filterManager.effectElements[6].p.v;
            this.matrixFilter.setAttribute("values", "0 0 0 0 " + e[0] + " 0 0 0 0 " + e[1] + " 0 0 0 0 " + e[2] + " 0 0 0 " + r + " 0") } }, SVGStrokeEffect.prototype.initialize = function() { var t, e, r, s, i = this.elem.layerElement.children || this.elem.layerElement.childNodes; for (1 === this.filterManager.effectElements[1].p.v ? (s = this.elem.maskManager.masksProperties.length, r = 0) : (r = this.filterManager.effectElements[0].p.v - 1, s = r + 1), e = document.createElementNS(svgNS, "g"), e.setAttribute("fill", "none"), e.setAttribute("stroke-linecap", "round"), e.setAttribute("stroke-dashoffset", 1), r; s > r; r += 1) t = document.createElementNS(svgNS, "path"), e.appendChild(t), this.paths.push({ p: t, m: r }); if (3 === this.filterManager.effectElements[10].p.v) { var a = document.createElementNS(svgNS, "mask"),
                n = "stms_" + randomString(10);
            a.setAttribute("id", n), a.setAttribute("mask-type", "alpha"), a.appendChild(e), this.elem.globalData.defs.appendChild(a); var o = document.createElementNS(svgNS, "g");
            o.setAttribute("mask", "url(#" + n + ")"), i[0] && o.appendChild(i[0]), this.elem.layerElement.appendChild(o), this.masker = a, e.setAttribute("stroke", "#fff") } else if (1 === this.filterManager.effectElements[10].p.v || 2 === this.filterManager.effectElements[10].p.v) { if (2 === this.filterManager.effectElements[10].p.v)
                for (var i = this.elem.layerElement.children || this.elem.layerElement.childNodes; i.length;) this.elem.layerElement.removeChild(i[0]);
            this.elem.layerElement.appendChild(e), this.elem.layerElement.removeAttribute("mask"), e.setAttribute("stroke", "#fff") }
        this.initialized = !0, this.pathMasker = e }, SVGStrokeEffect.prototype.renderFrame = function(t) { this.initialized || this.initialize(); var e, r, s, i = this.paths.length; for (e = 0; i > e; e += 1)
            if (r = this.elem.maskManager.viewData[this.paths[e].m], s = this.paths[e].p, (t || this.filterManager.mdf || r.prop.mdf) && s.setAttribute("d", r.lastPath), t || this.filterManager.effectElements[9].p.mdf || this.filterManager.effectElements[4].p.mdf || this.filterManager.effectElements[7].p.mdf || this.filterManager.effectElements[8].p.mdf || r.prop.mdf) { var a; if (0 !== this.filterManager.effectElements[7].p.v || 100 !== this.filterManager.effectElements[8].p.v) { var n = Math.min(this.filterManager.effectElements[7].p.v, this.filterManager.effectElements[8].p.v) / 100,
                        o = Math.max(this.filterManager.effectElements[7].p.v, this.filterManager.effectElements[8].p.v) / 100,
                        h = s.getTotalLength();
                    a = "0 0 0 " + h * n + " "; var l, p = h * (o - n),
                        m = 1 + 2 * this.filterManager.effectElements[4].p.v * this.filterManager.effectElements[9].p.v / 100,
                        f = Math.floor(p / m); for (l = 0; f > l; l += 1) a += "1 " + 2 * this.filterManager.effectElements[4].p.v * this.filterManager.effectElements[9].p.v / 100 + " ";
                    a += "0 " + 10 * h + " 0 0" } else a = "1 " + 2 * this.filterManager.effectElements[4].p.v * this.filterManager.effectElements[9].p.v / 100;
                s.setAttribute("stroke-dasharray", a) }
        if ((t || this.filterManager.effectElements[4].p.mdf) && this.pathMasker.setAttribute("stroke-width", 2 * this.filterManager.effectElements[4].p.v), (t || this.filterManager.effectElements[6].p.mdf) && this.pathMasker.setAttribute("opacity", this.filterManager.effectElements[6].p.v), (1 === this.filterManager.effectElements[10].p.v || 2 === this.filterManager.effectElements[10].p.v) && (t || this.filterManager.effectElements[3].p.mdf)) { var c = this.filterManager.effectElements[3].p.v;
            this.pathMasker.setAttribute("stroke", "rgb(" + bm_floor(255 * c[0]) + "," + bm_floor(255 * c[1]) + "," + bm_floor(255 * c[2]) + ")") } }, SVGTritoneFilter.prototype.renderFrame = function(t) { if (t || this.filterManager.mdf) { var e = this.filterManager.effectElements[0].p.v,
                r = this.filterManager.effectElements[1].p.v,
                s = this.filterManager.effectElements[2].p.v,
                i = s[0] + " " + r[0] + " " + e[0],
                a = s[1] + " " + r[1] + " " + e[1],
                n = s[2] + " " + r[2] + " " + e[2];
            this.feFuncR.setAttribute("tableValues", i), this.feFuncG.setAttribute("tableValues", a), this.feFuncB.setAttribute("tableValues", n) } }, SVGProLevelsFilter.prototype.createFeFunc = function(t, e) { var r = document.createElementNS(svgNS, t); return r.setAttribute("type", "table"), e.appendChild(r), r }, SVGProLevelsFilter.prototype.getTableValue = function(t, e, r, s, i) { for (var a, n, o = 0, h = 256, l = Math.min(t, e), p = Math.max(t, e), m = Array.call(null, { length: h }), f = 0, c = i - s, d = e - t; 256 >= o;) a = o / 256, n = l >= a ? 0 > d ? i : s : a >= p ? 0 > d ? s : i : s + c * Math.pow((a - t) / d, 1 / r), m[f++] = n, o += 256 / (h - 1); return m.join(" ") }, SVGProLevelsFilter.prototype.renderFrame = function(t) { if (t || this.filterManager.mdf) { var e, r = this.filterManager.effectElements;
            this.feFuncRComposed && (t || r[2].p.mdf || r[3].p.mdf || r[4].p.mdf || r[5].p.mdf || r[6].p.mdf) && (e = this.getTableValue(r[2].p.v, r[3].p.v, r[4].p.v, r[5].p.v, r[6].p.v), this.feFuncRComposed.setAttribute("tableValues", e), this.feFuncGComposed.setAttribute("tableValues", e), this.feFuncBComposed.setAttribute("tableValues", e)), this.feFuncR && (t || r[9].p.mdf || r[10].p.mdf || r[11].p.mdf || r[12].p.mdf || r[13].p.mdf) && (e = this.getTableValue(r[9].p.v, r[10].p.v, r[11].p.v, r[12].p.v, r[13].p.v), this.feFuncR.setAttribute("tableValues", e)), this.feFuncG && (t || r[16].p.mdf || r[17].p.mdf || r[18].p.mdf || r[19].p.mdf || r[20].p.mdf) && (e = this.getTableValue(r[16].p.v, r[17].p.v, r[18].p.v, r[19].p.v, r[20].p.v), this.feFuncG.setAttribute("tableValues", e)), this.feFuncB && (t || r[23].p.mdf || r[24].p.mdf || r[25].p.mdf || r[26].p.mdf || r[27].p.mdf) && (e = this.getTableValue(r[23].p.v, r[24].p.v, r[25].p.v, r[26].p.v, r[27].p.v), this.feFuncB.setAttribute("tableValues", e)), this.feFuncA && (t || r[30].p.mdf || r[31].p.mdf || r[32].p.mdf || r[33].p.mdf || r[34].p.mdf) && (e = this.getTableValue(r[30].p.v, r[31].p.v, r[32].p.v, r[33].p.v, r[34].p.v), this.feFuncA.setAttribute("tableValues", e)) } }, SVGDropShadowEffect.prototype.renderFrame = function(t) { if (t || this.filterManager.mdf) { if ((t || this.filterManager.effectElements[4].p.mdf) && this.feGaussianBlur.setAttribute("stdDeviation", this.filterManager.effectElements[4].p.v / 4), t || this.filterManager.effectElements[0].p.mdf) { var e = this.filterManager.effectElements[0].p.v;
                this.feFlood.setAttribute("flood-color", rgbToHex(Math.round(255 * e[0]), Math.round(255 * e[1]), Math.round(255 * e[2]))) } if ((t || this.filterManager.effectElements[1].p.mdf) && this.feFlood.setAttribute("flood-opacity", this.filterManager.effectElements[1].p.v / 255), t || this.filterManager.effectElements[2].p.mdf || this.filterManager.effectElements[3].p.mdf) { var r = this.filterManager.effectElements[3].p.v,
                    s = (this.filterManager.effectElements[2].p.v - 90) * degToRads,
                    i = r * Math.cos(s),
                    a = r * Math.sin(s);
                this.feOffset.setAttribute("dx", i), this.feOffset.setAttribute("dy", a) } } }, SVGEffects.prototype.renderFrame = function(t) { var e, r = this.filters.length; for (e = 0; r > e; e += 1) this.filters[e].renderFrame(t) }, createElement(SVGBaseElement, ICompElement), ICompElement.prototype.hide = function() { if (!this.hidden) { var t, e = this.elements.length; for (t = 0; e > t; t += 1) this.elements[t] && this.elements[t].hide();
            this.hidden = !0 } }, ICompElement.prototype.prepareFrame = function(t) { if (this._parent.prepareFrame.call(this, t), this.isVisible !== !1 || this.data.xt) { if (this.tm) { var e = this.tm.v;
                e === this.data.op && (e = this.data.op - 1), this.renderedFrame = e } else this.renderedFrame = t / this.data.sr; var r, s = this.elements.length; for (this.completeLayers || this.checkLayers(this.renderedFrame), r = 0; s > r; r += 1)(this.completeLayers || this.elements[r]) && this.elements[r].prepareFrame(this.renderedFrame - this.layers[r].st) } }, ICompElement.prototype.renderFrame = function(t) { var e, r = this._parent.renderFrame.call(this, t),
            s = this.layers.length; if (r === !1) return void this.hide(); for (this.hidden = !1, e = 0; s > e; e += 1)(this.completeLayers || this.elements[e]) && this.elements[e].renderFrame();
        this.firstFrame && (this.firstFrame = !1) }, ICompElement.prototype.setElements = function(t) { this.elements = t }, ICompElement.prototype.getElements = function() { return this.elements }, ICompElement.prototype.destroy = function() { this._parent.destroy.call(this._parent); var t, e = this.layers.length; for (t = 0; e > t; t += 1) this.elements[t] && this.elements[t].destroy() }, ICompElement.prototype.checkLayers = SVGRenderer.prototype.checkLayers, ICompElement.prototype.buildItem = SVGRenderer.prototype.buildItem, ICompElement.prototype.buildAllItems = SVGRenderer.prototype.buildAllItems, ICompElement.prototype.buildElementParenting = SVGRenderer.prototype.buildElementParenting, ICompElement.prototype.createItem = SVGRenderer.prototype.createItem, ICompElement.prototype.createImage = SVGRenderer.prototype.createImage, ICompElement.prototype.createComp = SVGRenderer.prototype.createComp, ICompElement.prototype.createSolid = SVGRenderer.prototype.createSolid, ICompElement.prototype.createShape = SVGRenderer.prototype.createShape, ICompElement.prototype.createText = SVGRenderer.prototype.createText, ICompElement.prototype.createBase = SVGRenderer.prototype.createBase, ICompElement.prototype.appendElementInPos = SVGRenderer.prototype.appendElementInPos, ICompElement.prototype.checkPendingElements = SVGRenderer.prototype.checkPendingElements, ICompElement.prototype.addPendingElement = SVGRenderer.prototype.addPendingElement, createElement(SVGBaseElement, IImageElement), IImageElement.prototype.createElements = function() { var t = this.globalData.getAssetsPath(this.assetData);
        this._parent.createElements.call(this), this.innerElem = document.createElementNS(svgNS, "image"), this.innerElem.setAttribute("width", this.assetData.w + "px"), this.innerElem.setAttribute("height", this.assetData.h + "px"), this.innerElem.setAttribute("preserveAspectRatio", "xMidYMid slice"), this.innerElem.setAttributeNS("http://www.w3.org/1999/xlink", "href", t), this.maskedElement = this.innerElem, this.layerElement.appendChild(this.innerElem), this.data.ln && this.layerElement.setAttribute("id", this.data.ln), this.data.cl && this.layerElement.setAttribute("class", this.data.cl) }, IImageElement.prototype.hide = function() { this.hidden || (this.layerElement.style.display = "none", this.hidden = !0) }, IImageElement.prototype.renderFrame = function(t) { var e = this._parent.renderFrame.call(this, t); return e === !1 ? void this.hide() : (this.hidden && (this.hidden = !1, this.layerElement.style.display = "block"), void(this.firstFrame && (this.firstFrame = !1))) }, IImageElement.prototype.destroy = function() { this._parent.destroy.call(this._parent), this.innerElem = null }, createElement(SVGBaseElement, IShapeElement), IShapeElement.prototype.lcEnum = { 1: "butt", 2: "round", 3: "butt" }, IShapeElement.prototype.ljEnum = { 1: "miter", 2: "round", 3: "butt" }, IShapeElement.prototype.buildExpressionInterface = function() {}, IShapeElement.prototype.createElements = function() { this._parent.createElements.call(this), this.searchShapes(this.shapesData, this.viewData, this.layerElement, this.dynamicProperties, 0), (!this.data.hd || this.data.td) && styleUnselectableDiv(this.layerElement) }, IShapeElement.prototype.setGradientData = function(t, e, r) { var s, i = "gr_" + randomString(10);
        s = 1 === e.t ? document.createElementNS(svgNS, "linearGradient") : document.createElementNS(svgNS, "radialGradient"), s.setAttribute("id", i), s.setAttribute("spreadMethod", "pad"), s.setAttribute("gradientUnits", "userSpaceOnUse"); var a, n, o, h = []; for (o = 4 * e.g.p, n = 0; o > n; n += 4) a = document.createElementNS(svgNS, "stop"), s.appendChild(a), h.push(a);
        t.setAttribute("gf" === e.ty ? "fill" : "stroke", "url(#" + i + ")"), this.globalData.defs.appendChild(s), r.gf = s, r.cst = h }, IShapeElement.prototype.setGradientOpacity = function(t, e, r) { if (t.g.k.k[0].s && t.g.k.k[0].s.length > 4 * t.g.p || t.g.k.k.length > 4 * t.g.p) { var s, i, a, n, o = document.createElementNS(svgNS, "mask"),
                h = document.createElementNS(svgNS, "path");
            o.appendChild(h); var l = "op_" + randomString(10),
                p = "mk_" + randomString(10);
            o.setAttribute("id", p), s = 1 === t.t ? document.createElementNS(svgNS, "linearGradient") : document.createElementNS(svgNS, "radialGradient"), s.setAttribute("id", l), s.setAttribute("spreadMethod", "pad"), s.setAttribute("gradientUnits", "userSpaceOnUse"), n = t.g.k.k[0].s ? t.g.k.k[0].s.length : t.g.k.k.length; var m = []; for (a = 4 * t.g.p; n > a; a += 2) i = document.createElementNS(svgNS, "stop"), i.setAttribute("stop-color", "rgb(255,255,255)"), s.appendChild(i), m.push(i); return h.setAttribute("gf" === t.ty ? "fill" : "stroke", "url(#" + l + ")"), this.globalData.defs.appendChild(s), this.globalData.defs.appendChild(o), e.of = s, e.ost = m, r.msElem = h, p } }, IShapeElement.prototype.searchShapes = function(t, e, r, s, i, a) {
        a = a || [];
        var n, o, h, l, p, m = [].concat(a),
            f = t.length - 1,
            c = [],
            d = [];
        for (n = f; n >= 0; n -= 1)
            if ("fl" == t[n].ty || "st" == t[n].ty || "gf" == t[n].ty || "gs" == t[n].ty) {
                e[n] = {}, l = { type: t[n].ty, d: "", ld: "", lvl: i, mdf: !1 };
                var u = document.createElementNS(svgNS, "path");
                if (e[n].o = PropertyFactory.getProp(this, t[n].o, 0, .01, s), ("st" == t[n].ty || "gs" == t[n].ty) && (u.setAttribute("stroke-linecap", this.lcEnum[t[n].lc] || "round"), u.setAttribute("stroke-linejoin", this.ljEnum[t[n].lj] || "round"), u.setAttribute("fill-opacity", "0"), 1 == t[n].lj && u.setAttribute("stroke-miterlimit", t[n].ml), e[n].w = PropertyFactory.getProp(this, t[n].w, 0, null, s), t[n].d)) { var y = PropertyFactory.getDashProp(this, t[n].d, "svg", s);
                    y.k || (u.setAttribute("stroke-dasharray", y.dasharray), u.setAttribute("stroke-dashoffset", y.dashoffset)), e[n].d = y }
                if ("fl" == t[n].ty || "st" == t[n].ty) e[n].c = PropertyFactory.getProp(this, t[n].c, 1, 255, s), r.appendChild(u);
                else {
                    e[n].g = PropertyFactory.getGradientProp(this, t[n].g, s), 2 == t[n].t && (e[n].h = PropertyFactory.getProp(this, t[n].h, 1, .01, s), e[n].a = PropertyFactory.getProp(this, t[n].a, 1, degToRads, s)), e[n].s = PropertyFactory.getProp(this, t[n].s, 1, null, s), e[n].e = PropertyFactory.getProp(this, t[n].e, 1, null, s), this.setGradientData(u, t[n], e[n], l);

                    var g = this.setGradientOpacity(t[n], e[n], l);
                    g && u.setAttribute("mask", "url(#" + g + ")"), e[n].elem = u, r.appendChild(u)
                }
                2 === t[n].r && u.setAttribute("fill-rule", "evenodd"), t[n].ln && u.setAttribute("id", t[n].ln), t[n].cl && u.setAttribute("class", t[n].cl), l.pElem = u, this.stylesList.push(l), e[n].style = l, c.push(l)
            } else if ("gr" == t[n].ty) { e[n] = { it: [] }; var v = document.createElementNS(svgNS, "g");
            r.appendChild(v), e[n].gr = v, this.searchShapes(t[n].it, e[n].it, v, s, i + 1, m) } else if ("tr" == t[n].ty) e[n] = { transform: { op: PropertyFactory.getProp(this, t[n].o, 0, .01, s), mProps: PropertyFactory.getProp(this, t[n], 2, null, s) }, elements: [] }, p = e[n].transform, m.push(p);
        else if ("sh" == t[n].ty || "rc" == t[n].ty || "el" == t[n].ty || "sr" == t[n].ty) { e[n] = { elements: [], caches: [], styles: [], transformers: m, lStr: "" }; var b = 4; for ("rc" == t[n].ty ? b = 5 : "el" == t[n].ty ? b = 6 : "sr" == t[n].ty && (b = 7), e[n].sh = ShapePropertyFactory.getShapeProp(this, t[n], b, s), e[n].lvl = i, this.shapes.push(e[n].sh), this.addShapeToModifiers(e[n]), h = this.stylesList.length, o = 0; h > o; o += 1) this.stylesList[o].closed || e[n].elements.push({ ty: this.stylesList[o].type, st: this.stylesList[o] }) } else if ("tm" == t[n].ty || "rd" == t[n].ty || "ms" == t[n].ty || "rp" == t[n].ty) { var E = ShapeModifiers.getModifier(t[n].ty);
            E.init(this, t[n], s), this.shapeModifiers.push(E), d.push(E), e[n] = E }
        for (f = c.length, n = 0; f > n; n += 1) c[n].closed = !0;
        for (f = d.length, n = 0; f > n; n += 1) d[n].closed = !0
    }, IShapeElement.prototype.addShapeToModifiers = function(t) { var e, r = this.shapeModifiers.length; for (e = 0; r > e; e += 1) this.shapeModifiers[e].addShape(t) }, IShapeElement.prototype.renderModifiers = function() { if (this.shapeModifiers.length) { var t, e = this.shapes.length; for (t = 0; e > t; t += 1) this.shapes[t].reset(); for (e = this.shapeModifiers.length, t = e - 1; t >= 0; t -= 1) this.shapeModifiers[t].processShapes(this.firstFrame) } }, IShapeElement.prototype.renderFrame = function(t) { var e = this._parent.renderFrame.call(this, t); return e === !1 ? void this.hide() : (this.globalToLocal([0, 0, 0]), this.hidden && (this.layerElement.style.display = "block", this.hidden = !1), this.renderModifiers(), void this.renderShape(null, null, !0, null)) }, IShapeElement.prototype.hide = function() { if (!this.hidden) { this.layerElement.style.display = "none"; var t, e = this.stylesList.length; for (t = e - 1; t >= 0; t -= 1) "0" !== this.stylesList[t].ld && (this.stylesList[t].ld = "0", this.stylesList[t].pElem.style.display = "none", this.stylesList[t].pElem.parentNode && (this.stylesList[t].parent = this.stylesList[t].pElem.parentNode));
            this.hidden = !0 } }, IShapeElement.prototype.renderShape = function(t, e, r, s) { var i, a; if (!t)
            for (t = this.shapesData, a = this.stylesList.length, i = 0; a > i; i += 1) this.stylesList[i].d = "", this.stylesList[i].mdf = !1;
        e || (e = this.viewData), a = t.length - 1; var n; for (i = a; i >= 0; i -= 1) n = t[i].ty, "tr" == n ? ((this.firstFrame || e[i].transform.op.mdf && s) && s.setAttribute("opacity", e[i].transform.op.v), (this.firstFrame || e[i].transform.mProps.mdf && s) && s.setAttribute("transform", e[i].transform.mProps.v.to2dCSS())) : "sh" == n || "el" == n || "rc" == n || "sr" == n ? this.renderPath(t[i], e[i]) : "fl" == n ? this.renderFill(t[i], e[i]) : "gf" == n ? this.renderGradient(t[i], e[i]) : "gs" == n ? (this.renderGradient(t[i], e[i]), this.renderStroke(t[i], e[i])) : "st" == n ? this.renderStroke(t[i], e[i]) : "gr" == n && this.renderShape(t[i].it, e[i].it, !1, e[i].gr); if (r) { for (a = this.stylesList.length, i = 0; a > i; i += 1) "0" === this.stylesList[i].ld && (this.stylesList[i].ld = "1", this.stylesList[i].pElem.style.display = "block"), (this.stylesList[i].mdf || this.firstFrame) && (this.stylesList[i].pElem.setAttribute("d", this.stylesList[i].d), this.stylesList[i].msElem && this.stylesList[i].msElem.setAttribute("d", this.stylesList[i].d));
            this.firstFrame && (this.firstFrame = !1) } }, IShapeElement.prototype.renderPath = function(t, e) { var r, s, i, a, n, o, h, l, p = e.elements.length,
            m = e.lvl; for (l = 0; p > l; l += 1) { o = e.sh.mdf || this.firstFrame, n = "M0 0"; var f = e.sh.paths; if (a = f._length, e.elements[l].st.lvl < m) { for (var c, d = this.mHelper.reset(), u = m - e.elements[l].st.lvl, y = e.transformers.length - 1; u > 0;) o = e.transformers[y].mProps.mdf || o, c = e.transformers[y].mProps.v.props, d.transform(c[0], c[1], c[2], c[3], c[4], c[5], c[6], c[7], c[8], c[9], c[10], c[11], c[12], c[13], c[14], c[15]), u--, y--; if (o) { for (i = 0; a > i; i += 1)
                        if (h = f.shapes[i], h && h._length) { for (r = h._length, s = 1; r > s; s += 1) 1 == s && (n += " M" + d.applyToPointStringified(h.v[0][0], h.v[0][1])), n += " C" + d.applyToPointStringified(h.o[s - 1][0], h.o[s - 1][1]) + " " + d.applyToPointStringified(h.i[s][0], h.i[s][1]) + " " + d.applyToPointStringified(h.v[s][0], h.v[s][1]);
                            1 == r && (n += " M" + d.applyToPointStringified(h.v[0][0], h.v[0][1])), h.c && (n += " C" + d.applyToPointStringified(h.o[s - 1][0], h.o[s - 1][1]) + " " + d.applyToPointStringified(h.i[0][0], h.i[0][1]) + " " + d.applyToPointStringified(h.v[0][0], h.v[0][1]), n += "z") }
                    e.caches[l] = n } else n = e.caches[l] } else if (o) { for (i = 0; a > i; i += 1)
                    if (h = f.shapes[i], h && h._length) { for (r = h._length, s = 1; r > s; s += 1) 1 == s && (n += " M" + h.v[0].join(",")), n += " C" + h.o[s - 1].join(",") + " " + h.i[s].join(",") + " " + h.v[s].join(",");
                        1 == r && (n += " M" + h.v[0].join(",")), h.c && r && (n += " C" + h.o[s - 1].join(",") + " " + h.i[0].join(",") + " " + h.v[0].join(","), n += "z") }
                e.caches[l] = n } else n = e.caches[l];
            e.elements[l].st.d += n, e.elements[l].st.mdf = o || e.elements[l].st.mdf } }, IShapeElement.prototype.renderFill = function(t, e) { var r = e.style;
        (e.c.mdf || this.firstFrame) && r.pElem.setAttribute("fill", "rgb(" + bm_floor(e.c.v[0]) + "," + bm_floor(e.c.v[1]) + "," + bm_floor(e.c.v[2]) + ")"), (e.o.mdf || this.firstFrame) && r.pElem.setAttribute("fill-opacity", e.o.v) }, IShapeElement.prototype.renderGradient = function(t, e) { var r = e.gf,
            s = e.of,
            i = e.s.v,
            a = e.e.v; if (e.o.mdf || this.firstFrame) { var n = "gf" === t.ty ? "fill-opacity" : "stroke-opacity";
            e.elem.setAttribute(n, e.o.v) } if (e.s.mdf || this.firstFrame) { var o = 1 === t.t ? "x1" : "cx",
                h = "x1" === o ? "y1" : "cy";
            r.setAttribute(o, i[0]), r.setAttribute(h, i[1]), s && (s.setAttribute(o, i[0]), s.setAttribute(h, i[1])) } var l, p, m, f; if (e.g.cmdf || this.firstFrame) { l = e.cst; var c = e.g.c; for (m = l.length, p = 0; m > p; p += 1) f = l[p], f.setAttribute("offset", c[4 * p] + "%"), f.setAttribute("stop-color", "rgb(" + c[4 * p + 1] + "," + c[4 * p + 2] + "," + c[4 * p + 3] + ")") } if (s && (e.g.omdf || this.firstFrame)) { l = e.ost; var d = e.g.o; for (m = l.length, p = 0; m > p; p += 1) f = l[p], f.setAttribute("offset", d[2 * p] + "%"), f.setAttribute("stop-opacity", d[2 * p + 1]) } if (1 === t.t)(e.e.mdf || this.firstFrame) && (r.setAttribute("x2", a[0]), r.setAttribute("y2", a[1]), s && (s.setAttribute("x2", a[0]), s.setAttribute("y2", a[1])));
        else { var u; if ((e.s.mdf || e.e.mdf || this.firstFrame) && (u = Math.sqrt(Math.pow(i[0] - a[0], 2) + Math.pow(i[1] - a[1], 2)), r.setAttribute("r", u), s && s.setAttribute("r", u)), e.e.mdf || e.h.mdf || e.a.mdf || this.firstFrame) { u || (u = Math.sqrt(Math.pow(i[0] - a[0], 2) + Math.pow(i[1] - a[1], 2))); var y = Math.atan2(a[1] - i[1], a[0] - i[0]),
                    g = e.h.v >= 1 ? .99 : e.h.v <= -1 ? -.99 : e.h.v,
                    v = u * g,
                    b = Math.cos(y + e.a.v) * v + i[0],
                    E = Math.sin(y + e.a.v) * v + i[1];
                r.setAttribute("fx", b), r.setAttribute("fy", E), s && (s.setAttribute("fx", b), s.setAttribute("fy", E)) } } }, IShapeElement.prototype.renderStroke = function(t, e) { var r = e.style,
            s = e.d;
        s && s.k && (s.mdf || this.firstFrame) && (r.pElem.setAttribute("stroke-dasharray", s.dasharray), r.pElem.setAttribute("stroke-dashoffset", s.dashoffset)), e.c && (e.c.mdf || this.firstFrame) && r.pElem.setAttribute("stroke", "rgb(" + bm_floor(e.c.v[0]) + "," + bm_floor(e.c.v[1]) + "," + bm_floor(e.c.v[2]) + ")"), (e.o.mdf || this.firstFrame) && r.pElem.setAttribute("stroke-opacity", e.o.v), (e.w.mdf || this.firstFrame) && (r.pElem.setAttribute("stroke-width", e.w.v), r.msElem && r.msElem.setAttribute("stroke-width", e.w.v)) }, IShapeElement.prototype.destroy = function() { this._parent.destroy.call(this._parent), this.shapeData = null, this.viewData = null, this.parentContainer = null, this.placeholder = null }, createElement(SVGBaseElement, ISolidElement), ISolidElement.prototype.createElements = function() { this._parent.createElements.call(this); var t = document.createElementNS(svgNS, "rect");
        t.setAttribute("width", this.data.sw), t.setAttribute("height", this.data.sh), t.setAttribute("fill", this.data.sc), this.layerElement.appendChild(t), this.innerElem = t, this.data.ln && this.layerElement.setAttribute("id", this.data.ln), this.data.cl && this.layerElement.setAttribute("class", this.data.cl) }, ISolidElement.prototype.hide = IImageElement.prototype.hide, ISolidElement.prototype.renderFrame = IImageElement.prototype.renderFrame, ISolidElement.prototype.destroy = IImageElement.prototype.destroy;
    var animationManager = function() {
            function t(t) { for (var e = 0, r = t.target; C > e;) x[e].animation === r && (x.splice(e, 1), e -= 1, C -= 1, r.isPaused || s()), e += 1 }

            function e(t, e) { if (!t) return null; for (var r = 0; C > r;) { if (x[r].elem == t && null !== x[r].elem) return x[r].animation;
                    r += 1 } var s = new AnimationItem; return i(s, t), s.setData(t, e), s }

            function r() { M += 1, E() }

            function s() { M -= 1, 0 === M && (k = !0) }

            function i(e, i) { e.addEventListener("destroy", t), e.addEventListener("_active", r), e.addEventListener("_idle", s), x.push({ elem: i, animation: e }), C += 1 }

            function a(t) { var e = new AnimationItem; return i(e, null), e.setParams(t), e }

            function n(t, e) { var r; for (r = 0; C > r; r += 1) x[r].animation.setSpeed(t, e) }

            function o(t, e) { var r; for (r = 0; C > r; r += 1) x[r].animation.setDirection(t, e) }

            function h(t) { var e; for (e = 0; C > e; e += 1) x[e].animation.play(t) }

            function l(t, e) { S = Date.now(); var r; for (r = 0; C > r; r += 1) x[r].animation.moveFrame(t, e) }

            function p(t) { var e, r = t - S; for (e = 0; C > e; e += 1) x[e].animation.advanceTime(r);
                S = t, k || requestAnimationFrame(p) }

            function m(t) { S = t, requestAnimationFrame(p) }

            function f(t) { var e; for (e = 0; C > e; e += 1) x[e].animation.pause(t) }

            function c(t, e, r) { var s; for (s = 0; C > s; s += 1) x[s].animation.goToAndStop(t, e, r) }

            function d(t) { var e; for (e = 0; C > e; e += 1) x[e].animation.stop(t) }

            function u(t) { var e; for (e = 0; C > e; e += 1) x[e].animation.togglePause(t) }

            function y(t) { var e; for (e = C - 1; e >= 0; e -= 1) x[e].animation.destroy(t) }

            function g(t, r, s) { var i, a = document.getElementsByClassName("bodymovin"),
                    n = a.length; for (i = 0; n > i; i += 1) s && a[i].setAttribute("data-bm-type", s), e(a[i], t); if (r && 0 === n) { s || (s = "svg"); var o = document.getElementsByTagName("body")[0];
                    o.innerHTML = ""; var h = document.createElement("div");
                    h.style.width = "100%", h.style.height = "100%", h.setAttribute("data-bm-type", s), o.appendChild(h), e(h, t) } }

            function v() { var t; for (t = 0; C > t; t += 1) x[t].animation.resize() }

            function b() { requestAnimationFrame(m) }

            function E() { k && (k = !1, requestAnimationFrame(m)) } var P = {},
                x = [],
                S = 0,
                C = 0,
                k = !0,
                M = 0; return setTimeout(b, 0), P.registerAnimation = e, P.loadAnimation = a, P.setSpeed = n, P.setDirection = o, P.play = h, P.moveFrame = l, P.pause = f, P.stop = d, P.togglePause = u, P.searchAnimations = g, P.resize = v, P.start = b, P.goToAndStop = c, P.destroy = y, P }(),
        AnimationItem = function() { this._cbs = [], this.name = "", this.path = "", this.isLoaded = !1, this.currentFrame = 0, this.currentRawFrame = 0, this.totalFrames = 0, this.frameRate = 0, this.frameMult = 0, this.playSpeed = 1, this.playDirection = 1, this.pendingElements = 0, this.playCount = 0, this.prerenderFramesFlag = !0, this.animationData = {}, this.layers = [], this.assets = [], this.isPaused = !0, this.autoplay = !1, this.loop = !0, this.renderer = null, this.animationID = randomString(10), this.scaleMode = "fit", this.assetsPath = "", this.timeCompleted = 0, this.segmentPos = 0, this.subframeEnabled = subframeEnabled, this.segments = [], this.pendingSegment = !1, this._idle = !0, this.projectInterface = ProjectInterface() };
    AnimationItem.prototype.setParams = function(t) { var e = this;
        t.context && (this.context = t.context), (t.wrapper || t.container) && (this.wrapper = t.wrapper || t.container); var r = t.animType ? t.animType : t.renderer ? t.renderer : "svg"; switch (r) {
            case "canvas":
                this.renderer = new CanvasRenderer(this, t.rendererSettings); break;
            case "svg":
                this.renderer = new SVGRenderer(this, t.rendererSettings); break;
            case "hybrid":
            case "html":
            default:
                this.renderer = new HybridRenderer(this, t.rendererSettings) } if (this.renderer.setProjectInterface(this.projectInterface), this.animType = r, "" === t.loop || null === t.loop || (this.loop = t.loop === !1 ? !1 : t.loop === !0 ? !0 : parseInt(t.loop)), this.autoplay = "autoplay" in t ? t.autoplay : !0, this.name = t.name ? t.name : "", this.prerenderFramesFlag = "prerender" in t ? t.prerender : !0, this.autoloadSegments = t.hasOwnProperty("autoloadSegments") ? t.autoloadSegments : !0, t.animationData) e.configAnimation(t.animationData);
        else if (t.path) { "json" != t.path.substr(-4) && ("/" != t.path.substr(-1, 1) && (t.path += "/"), t.path += "data.json"); var s = new XMLHttpRequest;
            this.path = -1 != t.path.lastIndexOf("\\") ? t.path.substr(0, t.path.lastIndexOf("\\") + 1) : t.path.substr(0, t.path.lastIndexOf("/") + 1), this.assetsPath = t.assetsPath, this.fileName = t.path.substr(t.path.lastIndexOf("/") + 1), this.fileName = this.fileName.substr(0, this.fileName.lastIndexOf(".json")), s.open("GET", t.path, !0), s.send(), s.onreadystatechange = function() { if (4 == s.readyState)
                    if (200 == s.status) e.configAnimation(JSON.parse(s.responseText));
                    else try { var t = JSON.parse(s.responseText);
                        e.configAnimation(t) } catch (r) {} } } }, AnimationItem.prototype.setData = function(t, e) { var r = { wrapper: t, animationData: e ? "object" == typeof e ? e : JSON.parse(e) : null },
            s = t.attributes;
        r.path = s.getNamedItem("data-animation-path") ? s.getNamedItem("data-animation-path").value : s.getNamedItem("data-bm-path") ? s.getNamedItem("data-bm-path").value : s.getNamedItem("bm-path") ? s.getNamedItem("bm-path").value : "", r.animType = s.getNamedItem("data-anim-type") ? s.getNamedItem("data-anim-type").value : s.getNamedItem("data-bm-type") ? s.getNamedItem("data-bm-type").value : s.getNamedItem("bm-type") ? s.getNamedItem("bm-type").value : s.getNamedItem("data-bm-renderer") ? s.getNamedItem("data-bm-renderer").value : s.getNamedItem("bm-renderer") ? s.getNamedItem("bm-renderer").value : "canvas"; var i = s.getNamedItem("data-anim-loop") ? s.getNamedItem("data-anim-loop").value : s.getNamedItem("data-bm-loop") ? s.getNamedItem("data-bm-loop").value : s.getNamedItem("bm-loop") ? s.getNamedItem("bm-loop").value : ""; "" === i || (r.loop = "false" === i ? !1 : "true" === i ? !0 : parseInt(i)); var a = s.getNamedItem("data-anim-autoplay") ? s.getNamedItem("data-anim-autoplay").value : s.getNamedItem("data-bm-autoplay") ? s.getNamedItem("data-bm-autoplay").value : s.getNamedItem("bm-autoplay") ? s.getNamedItem("bm-autoplay").value : !0;
        r.autoplay = "false" !== a, r.name = s.getNamedItem("data-name") ? s.getNamedItem("data-name").value : s.getNamedItem("data-bm-name") ? s.getNamedItem("data-bm-name").value : s.getNamedItem("bm-name") ? s.getNamedItem("bm-name").value : ""; var n = s.getNamedItem("data-anim-prerender") ? s.getNamedItem("data-anim-prerender").value : s.getNamedItem("data-bm-prerender") ? s.getNamedItem("data-bm-prerender").value : s.getNamedItem("bm-prerender") ? s.getNamedItem("bm-prerender").value : ""; "false" === n && (r.prerender = !1), this.setParams(r) }, AnimationItem.prototype.includeLayers = function(t) { t.op > this.animationData.op && (this.animationData.op = t.op, this.totalFrames = Math.floor(t.op - this.animationData.ip), this.animationData.tf = this.totalFrames); var e, r, s = this.animationData.layers,
            i = s.length,
            a = t.layers,
            n = a.length; for (r = 0; n > r; r += 1)
            for (e = 0; i > e;) { if (s[e].id == a[r].id) { s[e] = a[r]; break }
                e += 1 }
        if ((t.chars || t.fonts) && (this.renderer.globalData.fontManager.addChars(t.chars), this.renderer.globalData.fontManager.addFonts(t.fonts, this.renderer.globalData.defs)), t.assets)
            for (i = t.assets.length, e = 0; i > e; e += 1) this.animationData.assets.push(t.assets[e]);
        this.animationData.__complete = !1, dataManager.completeData(this.animationData, this.renderer.globalData.fontManager), this.renderer.includeLayers(t.layers), expressionsPlugin && expressionsPlugin.initExpressions(this), this.renderer.renderFrame(null), this.loadNextSegment() }, AnimationItem.prototype.loadNextSegment = function() { var t = this.animationData.segments; if (!t || 0 === t.length || !this.autoloadSegments) return this.trigger("data_ready"), void(this.timeCompleted = this.animationData.tf); var e = t.shift();
        this.timeCompleted = e.time * this.frameRate; var r = new XMLHttpRequest,
            s = this,
            i = this.path + this.fileName + "_" + this.segmentPos + ".json";
        this.segmentPos += 1, r.open("GET", i, !0), r.send(), r.onreadystatechange = function() { if (4 == r.readyState)
                if (200 == r.status) s.includeLayers(JSON.parse(r.responseText));
                else try { var t = JSON.parse(r.responseText);
                    s.includeLayers(t) } catch (e) {} } }, AnimationItem.prototype.loadSegments = function() { var t = this.animationData.segments;
        t || (this.timeCompleted = this.animationData.tf), this.loadNextSegment() }, AnimationItem.prototype.configAnimation = function(t) { this.renderer && this.renderer.destroyed || (this.animationData = t, this.totalFrames = Math.floor(this.animationData.op - this.animationData.ip), this.animationData.tf = this.totalFrames, this.renderer.configAnimation(t), t.assets || (t.assets = []), t.comps && (t.assets = t.assets.concat(t.comps), t.comps = null), this.renderer.searchExtraCompositions(t.assets), this.layers = this.animationData.layers, this.assets = this.animationData.assets, this.frameRate = this.animationData.fr, this.firstFrame = Math.round(this.animationData.ip), this.frameMult = this.animationData.fr / 1e3, this.trigger("config_ready"), this.imagePreloader = new ImagePreloader, this.imagePreloader.setAssetsPath(this.assetsPath), this.imagePreloader.setPath(this.path), this.imagePreloader.loadasset(t.assets), this.loadSegments(), this.updaFrameModifier(), this.renderer.globalData.fontManager ? this.waitForFontsLoaded() : (dataManager.completeData(this.animationData, this.renderer.globalData.fontManager), this.checkLoaded())) }, AnimationItem.prototype.waitForFontsLoaded = function() {
        function t() { this.renderer.globalData.fontManager.loaded ? (dataManager.completeData(this.animationData, this.renderer.globalData.fontManager), this.checkLoaded()) : setTimeout(t.bind(this), 20) } return function() { t.bind(this)() } }(), AnimationItem.prototype.addPendingElement = function() { this.pendingElements += 1 }, AnimationItem.prototype.elementLoaded = function() { this.pendingElements--, this.checkLoaded() }, AnimationItem.prototype.checkLoaded = function() { 0 === this.pendingElements && (expressionsPlugin && expressionsPlugin.initExpressions(this), this.renderer.initItems(), setTimeout(function() { this.trigger("DOMLoaded") }.bind(this), 0), this.isLoaded = !0, this.gotoFrame(), this.autoplay && this.play()) }, AnimationItem.prototype.resize = function() { this.renderer.updateContainerSize() }, AnimationItem.prototype.setSubframe = function(t) { this.subframeEnabled = t ? !0 : !1 }, AnimationItem.prototype.gotoFrame = function() { this.currentFrame = this.subframeEnabled ? this.currentRawFrame : Math.floor(this.currentRawFrame), this.timeCompleted !== this.totalFrames && this.currentFrame > this.timeCompleted && (this.currentFrame = this.timeCompleted), this.trigger("enterFrame"), this.renderFrame() }, AnimationItem.prototype.renderFrame = function() { this.isLoaded !== !1 && this.renderer.renderFrame(this.currentFrame + this.firstFrame) }, AnimationItem.prototype.play = function(t) { t && this.name != t || this.isPaused === !0 && (this.isPaused = !1, this._idle && (this._idle = !1, this.trigger("_active"))) }, AnimationItem.prototype.pause = function(t) { t && this.name != t || this.isPaused === !1 && (this.isPaused = !0, this.pendingSegment || (this._idle = !0, this.trigger("_idle"))) }, AnimationItem.prototype.togglePause = function(t) { t && this.name != t || (this.isPaused === !0 ? this.play() : this.pause()) }, AnimationItem.prototype.stop = function(t) { t && this.name != t || (this.pause(), this.currentFrame = this.currentRawFrame = 0, this.playCount = 0, this.gotoFrame()) }, AnimationItem.prototype.goToAndStop = function(t, e, r) { r && this.name != r || (this.setCurrentRawFrameValue(e ? t : t * this.frameModifier), this.pause()) }, AnimationItem.prototype.goToAndPlay = function(t, e, r) { this.goToAndStop(t, e, r), this.play() }, AnimationItem.prototype.advanceTime = function(t) { return this.pendingSegment ? (this.pendingSegment = !1, this.adjustSegment(this.segments.shift()), void(this.isPaused && this.play())) : void(this.isPaused !== !0 && this.isLoaded !== !1 && this.setCurrentRawFrameValue(this.currentRawFrame + t * this.frameModifier)) }, AnimationItem.prototype.updateAnimation = function(t) { this.setCurrentRawFrameValue(this.totalFrames * t) }, AnimationItem.prototype.moveFrame = function(t, e) { e && this.name != e || this.setCurrentRawFrameValue(this.currentRawFrame + t) }, AnimationItem.prototype.adjustSegment = function(t) { this.playCount = 0, t[1] < t[0] ? (this.frameModifier > 0 && (this.playSpeed < 0 ? this.setSpeed(-this.playSpeed) : this.setDirection(-1)), this.totalFrames = t[0] - t[1], this.firstFrame = t[1], this.setCurrentRawFrameValue(this.totalFrames - .01)) : t[1] > t[0] && (this.frameModifier < 0 && (this.playSpeed < 0 ? this.setSpeed(-this.playSpeed) : this.setDirection(1)), this.totalFrames = t[1] - t[0], this.firstFrame = t[0], this.setCurrentRawFrameValue(0)), this.trigger("segmentStart") }, AnimationItem.prototype.setSegment = function(t, e) { var r = -1;
        this.isPaused && (this.currentRawFrame + this.firstFrame < t ? r = t : this.currentRawFrame + this.firstFrame > e && (r = e - t - .01)), this.firstFrame = t, this.totalFrames = e - t, -1 !== r && this.goToAndStop(r, !0) }, AnimationItem.prototype.playSegments = function(t, e) { if ("object" == typeof t[0]) { var r, s = t.length; for (r = 0; s > r; r += 1) this.segments.push(t[r]) } else this.segments.push(t);
        e && this.adjustSegment(this.segments.shift()), this.isPaused && this.play() }, AnimationItem.prototype.resetSegments = function(t) { this.segments.length = 0, this.segments.push([this.animationData.ip * this.frameRate, Math.floor(this.animationData.op - this.animationData.ip + this.animationData.ip * this.frameRate)]), t && this.adjustSegment(this.segments.shift()) }, AnimationItem.prototype.checkSegments = function() { this.segments.length && (this.pendingSegment = !0) }, AnimationItem.prototype.remove = function(t) { t && this.name != t || this.renderer.destroy() }, AnimationItem.prototype.destroy = function(t) { t && this.name != t || this.renderer && this.renderer.destroyed || (this.renderer.destroy(), this.trigger("destroy"), this._cbs = null, this.onEnterFrame = this.onLoopComplete = this.onComplete = this.onSegmentStart = this.onDestroy = null) }, AnimationItem.prototype.setCurrentRawFrameValue = function(t) { if (this.currentRawFrame = t, this.currentRawFrame >= this.totalFrames) { if (this.checkSegments(), this.loop === !1) return this.currentRawFrame = this.totalFrames - .01, this.gotoFrame(), this.pause(), void this.trigger("complete"); if (this.trigger("loopComplete"), this.playCount += 1, this.loop !== !0 && this.playCount == this.loop || this.pendingSegment) return this.currentRawFrame = this.totalFrames - .01, this.gotoFrame(), this.pause(), void this.trigger("complete");
            this.currentRawFrame = this.currentRawFrame % this.totalFrames } else if (this.currentRawFrame < 0) return this.checkSegments(), this.playCount -= 1, this.playCount < 0 && (this.playCount = 0), this.loop === !1 || this.pendingSegment ? (this.currentRawFrame = 0, this.gotoFrame(), this.pause(), void this.trigger("complete")) : (this.trigger("loopComplete"), this.currentRawFrame = (this.totalFrames + this.currentRawFrame) % this.totalFrames, void this.gotoFrame());
        this.gotoFrame() }, AnimationItem.prototype.setSpeed = function(t) { this.playSpeed = t, this.updaFrameModifier() }, AnimationItem.prototype.setDirection = function(t) { this.playDirection = 0 > t ? -1 : 1, this.updaFrameModifier() }, AnimationItem.prototype.updaFrameModifier = function() { this.frameModifier = this.frameMult * this.playSpeed * this.playDirection }, AnimationItem.prototype.getPath = function() { return this.path }, AnimationItem.prototype.getAssetsPath = function(t) { var e = ""; if (this.assetsPath) { var r = t.p; - 1 !== r.indexOf("images/") && (r = r.split("/")[1]), e = this.assetsPath + r } else e = this.path, e += t.u ? t.u : "", e += t.p; return e }, AnimationItem.prototype.getAssetData = function(t) { for (var e = 0, r = this.assets.length; r > e;) { if (t == this.assets[e].id) return this.assets[e];
            e += 1 } }, AnimationItem.prototype.hide = function() { this.renderer.hide() }, AnimationItem.prototype.show = function() { this.renderer.show() }, AnimationItem.prototype.getAssets = function() { return this.assets }, AnimationItem.prototype.trigger = function(t) { if (this._cbs && this._cbs[t]) switch (t) {
            case "enterFrame":
                this.triggerEvent(t, new BMEnterFrameEvent(t, this.currentFrame, this.totalFrames, this.frameMult)); break;
            case "loopComplete":
                this.triggerEvent(t, new BMCompleteLoopEvent(t, this.loop, this.playCount, this.frameMult)); break;
            case "complete":
                this.triggerEvent(t, new BMCompleteEvent(t, this.frameMult)); break;
            case "segmentStart":
                this.triggerEvent(t, new BMSegmentStartEvent(t, this.firstFrame, this.totalFrames)); break;
            case "destroy":
                this.triggerEvent(t, new BMDestroyEvent(t, this)); break;
            default:
                this.triggerEvent(t) }
        "enterFrame" === t && this.onEnterFrame && this.onEnterFrame.call(this, new BMEnterFrameEvent(t, this.currentFrame, this.totalFrames, this.frameMult)), "loopComplete" === t && this.onLoopComplete && this.onLoopComplete.call(this, new BMCompleteLoopEvent(t, this.loop, this.playCount, this.frameMult)), "complete" === t && this.onComplete && this.onComplete.call(this, new BMCompleteEvent(t, this.frameMult)), "segmentStart" === t && this.onSegmentStart && this.onSegmentStart.call(this, new BMSegmentStartEvent(t, this.firstFrame, this.totalFrames)), "destroy" === t && this.onDestroy && this.onDestroy.call(this, new BMDestroyEvent(t, this)) }, AnimationItem.prototype.addEventListener = _addEventListener, AnimationItem.prototype.removeEventListener = _removeEventListener, AnimationItem.prototype.triggerEvent = _triggerEvent, extendPrototype(BaseRenderer, CanvasRenderer), CanvasRenderer.prototype.createBase = function(t) { return new CVBaseElement(t, this, this.globalData) }, CanvasRenderer.prototype.createShape = function(t) { return new CVShapeElement(t, this, this.globalData) }, CanvasRenderer.prototype.createText = function(t) { return new CVTextElement(t, this, this.globalData) }, CanvasRenderer.prototype.createImage = function(t) { return new CVImageElement(t, this, this.globalData) }, CanvasRenderer.prototype.createComp = function(t) { return new CVCompElement(t, this, this.globalData) }, CanvasRenderer.prototype.createSolid = function(t) { return new CVSolidElement(t, this, this.globalData) }, CanvasRenderer.prototype.ctxTransform = function(t) { if (1 !== t[0] || 0 !== t[1] || 0 !== t[4] || 1 !== t[5] || 0 !== t[12] || 0 !== t[13]) { if (!this.renderConfig.clearCanvas) return void this.canvasContext.transform(t[0], t[1], t[4], t[5], t[12], t[13]);
            this.transformMat.cloneFromProps(t), this.transformMat.transform(this.contextData.cTr.props[0], this.contextData.cTr.props[1], this.contextData.cTr.props[2], this.contextData.cTr.props[3], this.contextData.cTr.props[4], this.contextData.cTr.props[5], this.contextData.cTr.props[6], this.contextData.cTr.props[7], this.contextData.cTr.props[8], this.contextData.cTr.props[9], this.contextData.cTr.props[10], this.contextData.cTr.props[11], this.contextData.cTr.props[12], this.contextData.cTr.props[13], this.contextData.cTr.props[14], this.contextData.cTr.props[15]), this.contextData.cTr.cloneFromProps(this.transformMat.props); var e = this.contextData.cTr.props;
            this.canvasContext.setTransform(e[0], e[1], e[4], e[5], e[12], e[13]) } }, CanvasRenderer.prototype.ctxOpacity = function(t) { if (1 !== t) { if (!this.renderConfig.clearCanvas) return void(this.canvasContext.globalAlpha *= 0 > t ? 0 : t);
            this.contextData.cO *= 0 > t ? 0 : t, this.canvasContext.globalAlpha = this.contextData.cO } }, CanvasRenderer.prototype.reset = function() { return this.renderConfig.clearCanvas ? (this.contextData.cArrPos = 0, this.contextData.cTr.reset(), void(this.contextData.cO = 1)) : void this.canvasContext.restore() }, CanvasRenderer.prototype.save = function(t) { if (!this.renderConfig.clearCanvas) return void this.canvasContext.save();
        t && this.canvasContext.save(); var e = this.contextData.cTr.props;
        (null === this.contextData.saved[this.contextData.cArrPos] || void 0 === this.contextData.saved[this.contextData.cArrPos]) && (this.contextData.saved[this.contextData.cArrPos] = new Array(16)); var r, s = this.contextData.saved[this.contextData.cArrPos]; for (r = 0; 16 > r; r += 1) s[r] = e[r];
        this.contextData.savedOp[this.contextData.cArrPos] = this.contextData.cO, this.contextData.cArrPos += 1 }, CanvasRenderer.prototype.restore = function(t) { if (!this.renderConfig.clearCanvas) return void this.canvasContext.restore();
        t && this.canvasContext.restore(), this.contextData.cArrPos -= 1; var e, r = this.contextData.saved[this.contextData.cArrPos],
            s = this.contextData.cTr.props; for (e = 0; 16 > e; e += 1) s[e] = r[e];
        this.canvasContext.setTransform(r[0], r[1], r[4], r[5], r[12], r[13]), r = this.contextData.savedOp[this.contextData.cArrPos], this.contextData.cO = r, this.canvasContext.globalAlpha = r }, CanvasRenderer.prototype.configAnimation = function(t) { this.animationItem.wrapper ? (this.animationItem.container = document.createElement("canvas"), this.animationItem.container.style.width = "100%", this.animationItem.container.style.height = "100%", this.animationItem.container.style.transformOrigin = this.animationItem.container.style.mozTransformOrigin = this.animationItem.container.style.webkitTransformOrigin = this.animationItem.container.style["-webkit-transform"] = "0px 0px 0px", this.animationItem.wrapper.appendChild(this.animationItem.container), this.canvasContext = this.animationItem.container.getContext("2d")) : this.canvasContext = this.renderConfig.context, this.data = t, this.globalData.canvasContext = this.canvasContext, this.globalData.renderer = this, this.globalData.isDashed = !1, this.globalData.totalFrames = Math.floor(t.tf), this.globalData.compWidth = t.w, this.globalData.compHeight = t.h, this.globalData.frameRate = t.fr, this.globalData.frameId = 0, this.globalData.compSize = { w: t.w, h: t.h }, this.globalData.progressiveLoad = this.renderConfig.progressiveLoad, this.layers = t.layers, this.transformCanvas = {}, this.transformCanvas.w = t.w, this.transformCanvas.h = t.h, this.globalData.fontManager = new FontManager, this.globalData.fontManager.addChars(t.chars), this.globalData.fontManager.addFonts(t.fonts, document.body), this.globalData.getAssetData = this.animationItem.getAssetData.bind(this.animationItem), this.globalData.getAssetsPath = this.animationItem.getAssetsPath.bind(this.animationItem), this.globalData.elementLoaded = this.animationItem.elementLoaded.bind(this.animationItem), this.globalData.addPendingElement = this.animationItem.addPendingElement.bind(this.animationItem), this.globalData.transformCanvas = this.transformCanvas, this.elements = Array.apply(null, { length: t.layers.length }), this.updateContainerSize() }, CanvasRenderer.prototype.updateContainerSize = function() { var t, e;
        this.animationItem.wrapper && this.animationItem.container ? (t = this.animationItem.wrapper.offsetWidth, e = this.animationItem.wrapper.offsetHeight, this.animationItem.container.setAttribute("width", t * this.renderConfig.dpr), this.animationItem.container.setAttribute("height", e * this.renderConfig.dpr)) : (t = this.canvasContext.canvas.width * this.renderConfig.dpr, e = this.canvasContext.canvas.height * this.renderConfig.dpr); var r, s; if (-1 !== this.renderConfig.preserveAspectRatio.indexOf("meet") || -1 !== this.renderConfig.preserveAspectRatio.indexOf("slice")) { var i = this.renderConfig.preserveAspectRatio.split(" "),
                a = i[1] || "meet",
                n = i[0] || "xMidYMid",
                o = n.substr(0, 4),
                h = n.substr(4);
            r = t / e, s = this.transformCanvas.w / this.transformCanvas.h, s > r && "meet" === a || r > s && "slice" === a ? (this.transformCanvas.sx = t / (this.transformCanvas.w / this.renderConfig.dpr), this.transformCanvas.sy = t / (this.transformCanvas.w / this.renderConfig.dpr)) : (this.transformCanvas.sx = e / (this.transformCanvas.h / this.renderConfig.dpr), this.transformCanvas.sy = e / (this.transformCanvas.h / this.renderConfig.dpr)), this.transformCanvas.tx = "xMid" === o && (r > s && "meet" === a || s > r && "slice" === a) ? (t - this.transformCanvas.w * (e / this.transformCanvas.h)) / 2 * this.renderConfig.dpr : "xMax" === o && (r > s && "meet" === a || s > r && "slice" === a) ? (t - this.transformCanvas.w * (e / this.transformCanvas.h)) * this.renderConfig.dpr : 0, this.transformCanvas.ty = "YMid" === h && (s > r && "meet" === a || r > s && "slice" === a) ? (e - this.transformCanvas.h * (t / this.transformCanvas.w)) / 2 * this.renderConfig.dpr : "YMax" === h && (s > r && "meet" === a || r > s && "slice" === a) ? (e - this.transformCanvas.h * (t / this.transformCanvas.w)) * this.renderConfig.dpr : 0 } else "none" == this.renderConfig.preserveAspectRatio ? (this.transformCanvas.sx = t / (this.transformCanvas.w / this.renderConfig.dpr), this.transformCanvas.sy = e / (this.transformCanvas.h / this.renderConfig.dpr), this.transformCanvas.tx = 0, this.transformCanvas.ty = 0) : (this.transformCanvas.sx = this.renderConfig.dpr, this.transformCanvas.sy = this.renderConfig.dpr, this.transformCanvas.tx = 0, this.transformCanvas.ty = 0);
        this.transformCanvas.props = [this.transformCanvas.sx, 0, 0, 0, 0, this.transformCanvas.sy, 0, 0, 0, 0, 1, 0, this.transformCanvas.tx, this.transformCanvas.ty, 0, 1]; var l, p = this.elements.length; for (l = 0; p > l; l += 1) this.elements[l] && 0 === this.elements[l].data.ty && this.elements[l].resize(this.globalData.transformCanvas) }, CanvasRenderer.prototype.destroy = function() { this.renderConfig.clearCanvas && (this.animationItem.wrapper.innerHTML = ""); var t, e = this.layers ? this.layers.length : 0; for (t = e - 1; t >= 0; t -= 1) this.elements[t].destroy();
        this.elements.length = 0, this.globalData.canvasContext = null, this.animationItem.container = null, this.destroyed = !0 }, CanvasRenderer.prototype.renderFrame = function(t) {
        if (!(this.renderedFrame == t && this.renderConfig.clearCanvas === !0 || this.destroyed || null === t)) {
            this.renderedFrame = t, this.globalData.frameNum = t - this.animationItem.firstFrame, this.globalData.frameId += 1, this.globalData.projectInterface.currentFrame = t, this.renderConfig.clearCanvas === !0 ? (this.reset(),
                this.canvasContext.save(), this.canvasContext.clearRect(this.transformCanvas.tx, this.transformCanvas.ty, this.transformCanvas.w * this.transformCanvas.sx, this.transformCanvas.h * this.transformCanvas.sy)) : this.save(), this.ctxTransform(this.transformCanvas.props), this.canvasContext.beginPath(), this.canvasContext.rect(0, 0, this.transformCanvas.w, this.transformCanvas.h), this.canvasContext.closePath(), this.canvasContext.clip();
            var e, r = this.layers.length;
            for (this.completeLayers || this.checkLayers(t), e = 0; r > e; e++)(this.completeLayers || this.elements[e]) && this.elements[e].prepareFrame(t - this.layers[e].st);
            for (e = r - 1; e >= 0; e -= 1)(this.completeLayers || this.elements[e]) && this.elements[e].renderFrame();
            this.renderConfig.clearCanvas !== !0 ? this.restore() : this.canvasContext.restore()
        }
    }, CanvasRenderer.prototype.buildItem = function(t) { var e = this.elements; if (!e[t] && 99 != this.layers[t].ty) { var r = this.createItem(this.layers[t], this, this.globalData);
            e[t] = r, r.initExpressions(), 0 === this.layers[t].ty && r.resize(this.globalData.transformCanvas) } }, CanvasRenderer.prototype.checkPendingElements = function() { for (; this.pendingElements.length;) { var t = this.pendingElements.pop();
            t.checkParenting() } }, CanvasRenderer.prototype.hide = function() { this.animationItem.container.style.display = "none" }, CanvasRenderer.prototype.show = function() { this.animationItem.container.style.display = "block" }, CanvasRenderer.prototype.searchExtraCompositions = function(t) {
        { var e, r = t.length;
            document.createElementNS(svgNS, "g") } for (e = 0; r > e; e += 1)
            if (t[e].xt) { var s = this.createComp(t[e], this.globalData.comp, this.globalData);
                s.initExpressions(), this.globalData.projectInterface.registerComposition(s) } }, extendPrototype(BaseRenderer, HybridRenderer), HybridRenderer.prototype.buildItem = SVGRenderer.prototype.buildItem, HybridRenderer.prototype.checkPendingElements = function() { for (; this.pendingElements.length;) { var t = this.pendingElements.pop();
            t.checkParenting() } }, HybridRenderer.prototype.appendElementInPos = function(t, e) { var r = t.getBaseElement(); if (r) { var s = this.layers[e]; if (s.ddd && this.supports3d) this.addTo3dContainer(r, e);
            else { for (var i, a = 0; e > a;) this.elements[a] && this.elements[a] !== !0 && this.elements[a].getBaseElement && (i = this.elements[a].getBaseElement()), a += 1;
                i ? s.ddd && this.supports3d || this.layerElement.insertBefore(r, i) : s.ddd && this.supports3d || this.layerElement.appendChild(r) } } }, HybridRenderer.prototype.createBase = function(t) { return new SVGBaseElement(t, this.layerElement, this.globalData, this) }, HybridRenderer.prototype.createShape = function(t) { return this.supports3d ? new HShapeElement(t, this.layerElement, this.globalData, this) : new IShapeElement(t, this.layerElement, this.globalData, this) }, HybridRenderer.prototype.createText = function(t) { return this.supports3d ? new HTextElement(t, this.layerElement, this.globalData, this) : new SVGTextElement(t, this.layerElement, this.globalData, this) }, HybridRenderer.prototype.createCamera = function(t) { return this.camera = new HCameraElement(t, this.layerElement, this.globalData, this), this.camera }, HybridRenderer.prototype.createImage = function(t) { return this.supports3d ? new HImageElement(t, this.layerElement, this.globalData, this) : new IImageElement(t, this.layerElement, this.globalData, this) }, HybridRenderer.prototype.createComp = function(t) { return this.supports3d ? new HCompElement(t, this.layerElement, this.globalData, this) : new ICompElement(t, this.layerElement, this.globalData, this) }, HybridRenderer.prototype.createSolid = function(t) { return this.supports3d ? new HSolidElement(t, this.layerElement, this.globalData, this) : new ISolidElement(t, this.layerElement, this.globalData, this) }, HybridRenderer.prototype.getThreeDContainer = function(t) { var e = document.createElement("div");
        styleDiv(e), e.style.width = this.globalData.compSize.w + "px", e.style.height = this.globalData.compSize.h + "px", e.style.transformOrigin = e.style.mozTransformOrigin = e.style.webkitTransformOrigin = "50% 50%"; var r = document.createElement("div");
        styleDiv(r), r.style.transform = r.style.webkitTransform = "matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1)", e.appendChild(r), this.resizerElem.appendChild(e); var s = { container: r, perspectiveElem: e, startPos: t, endPos: t }; return this.threeDElements.push(s), s }, HybridRenderer.prototype.build3dContainers = function() { var t, e, r = this.layers.length; for (t = 0; r > t; t += 1) this.layers[t].ddd ? (e || (e = this.getThreeDContainer(t)), e.endPos = Math.max(e.endPos, t)) : e = null }, HybridRenderer.prototype.addTo3dContainer = function(t, e) { for (var r = 0, s = this.threeDElements.length; s > r;) { if (e <= this.threeDElements[r].endPos) { for (var i, a = this.threeDElements[r].startPos; e > a;) this.elements[a] && this.elements[a].getBaseElement && (i = this.elements[a].getBaseElement()), a += 1;
                i ? this.threeDElements[r].container.insertBefore(t, i) : this.threeDElements[r].container.appendChild(t); break }
            r += 1 } }, HybridRenderer.prototype.configAnimation = function(t) { var e = document.createElement("div"),
            r = this.animationItem.wrapper;
        e.style.width = t.w + "px", e.style.height = t.h + "px", this.resizerElem = e, styleDiv(e), e.style.transformStyle = e.style.webkitTransformStyle = e.style.mozTransformStyle = "flat", r.appendChild(e), e.style.overflow = "hidden"; var s = document.createElementNS(svgNS, "svg");
        s.setAttribute("width", "1"), s.setAttribute("height", "1"), styleDiv(s), this.resizerElem.appendChild(s); var i = document.createElementNS(svgNS, "defs");
        s.appendChild(i), this.globalData.defs = i, this.data = t, this.globalData.getAssetData = this.animationItem.getAssetData.bind(this.animationItem), this.globalData.getAssetsPath = this.animationItem.getAssetsPath.bind(this.animationItem), this.globalData.elementLoaded = this.animationItem.elementLoaded.bind(this.animationItem), this.globalData.frameId = 0, this.globalData.compSize = { w: t.w, h: t.h }, this.globalData.frameRate = t.fr, this.layers = t.layers, this.globalData.fontManager = new FontManager, this.globalData.fontManager.addChars(t.chars), this.globalData.fontManager.addFonts(t.fonts, s), this.layerElement = this.resizerElem, this.build3dContainers(), this.updateContainerSize() }, HybridRenderer.prototype.destroy = function() { this.animationItem.wrapper.innerHTML = "", this.animationItem.container = null, this.globalData.defs = null; var t, e = this.layers ? this.layers.length : 0; for (t = 0; e > t; t++) this.elements[t].destroy();
        this.elements.length = 0, this.destroyed = !0, this.animationItem = null }, HybridRenderer.prototype.updateContainerSize = function() { var t, e, r, s, i = this.animationItem.wrapper.offsetWidth,
            a = this.animationItem.wrapper.offsetHeight,
            n = i / a,
            o = this.globalData.compSize.w / this.globalData.compSize.h;
        o > n ? (t = i / this.globalData.compSize.w, e = i / this.globalData.compSize.w, r = 0, s = (a - this.globalData.compSize.h * (i / this.globalData.compSize.w)) / 2) : (t = a / this.globalData.compSize.h, e = a / this.globalData.compSize.h, r = (i - this.globalData.compSize.w * (a / this.globalData.compSize.h)) / 2, s = 0), this.resizerElem.style.transform = this.resizerElem.style.webkitTransform = "matrix3d(" + t + ",0,0,0,0," + e + ",0,0,0,0,1,0," + r + "," + s + ",0,1)" }, HybridRenderer.prototype.renderFrame = SVGRenderer.prototype.renderFrame, HybridRenderer.prototype.hide = function() { this.resizerElem.style.display = "none" }, HybridRenderer.prototype.show = function() { this.resizerElem.style.display = "block" }, HybridRenderer.prototype.initItems = function() { if (this.buildAllItems(), this.camera) this.camera.setup();
        else { var t, e = this.globalData.compSize.w,
                r = this.globalData.compSize.h,
                s = this.threeDElements.length; for (t = 0; s > t; t += 1) this.threeDElements[t].perspectiveElem.style.perspective = this.threeDElements[t].perspectiveElem.style.webkitPerspective = Math.sqrt(Math.pow(e, 2) + Math.pow(r, 2)) + "px" } }, HybridRenderer.prototype.searchExtraCompositions = function(t) { var e, r = t.length,
            s = document.createElement("div"); for (e = 0; r > e; e += 1)
            if (t[e].xt) { var i = this.createComp(t[e], s, this.globalData.comp, null);
                i.initExpressions(), this.globalData.projectInterface.registerComposition(i) } }, createElement(BaseElement, CVBaseElement), CVBaseElement.prototype.createElements = function() { this.checkParenting() }, CVBaseElement.prototype.checkBlendMode = function(t) { if (t.blendMode !== this.data.bm) { t.blendMode = this.data.bm; var e = ""; switch (this.data.bm) {
                case 0:
                    e = "normal"; break;
                case 1:
                    e = "multiply"; break;
                case 2:
                    e = "screen"; break;
                case 3:
                    e = "overlay"; break;
                case 4:
                    e = "darken"; break;
                case 5:
                    e = "lighten"; break;
                case 6:
                    e = "color-dodge"; break;
                case 7:
                    e = "color-burn"; break;
                case 8:
                    e = "hard-light"; break;
                case 9:
                    e = "soft-light"; break;
                case 10:
                    e = "difference"; break;
                case 11:
                    e = "exclusion"; break;
                case 12:
                    e = "hue"; break;
                case 13:
                    e = "saturation"; break;
                case 14:
                    e = "color"; break;
                case 15:
                    e = "luminosity" }
            t.canvasContext.globalCompositeOperation = e } }, CVBaseElement.prototype.renderFrame = function(t) { if (3 === this.data.ty) return !1; if (this.checkBlendMode(0 === this.data.ty ? this.parentGlobalData : this.globalData), !this.isVisible) return this.isVisible;
        this.finalTransform.opMdf = this.finalTransform.op.mdf, this.finalTransform.matMdf = this.finalTransform.mProp.mdf, this.finalTransform.opacity = this.finalTransform.op.v; var e, r = this.finalTransform.mat; if (this.hierarchy) { var s, i = this.hierarchy.length; for (e = this.finalTransform.mProp.v.props, r.cloneFromProps(e), s = 0; i > s; s += 1) this.finalTransform.matMdf = this.hierarchy[s].finalTransform.mProp.mdf ? !0 : this.finalTransform.matMdf, e = this.hierarchy[s].finalTransform.mProp.v.props, r.transform(e[0], e[1], e[2], e[3], e[4], e[5], e[6], e[7], e[8], e[9], e[10], e[11], e[12], e[13], e[14], e[15]) } else t ? (e = this.finalTransform.mProp.v.props, r.cloneFromProps(e)) : r.cloneFromProps(this.finalTransform.mProp.v.props); return t && (e = t.mat.props, r.transform(e[0], e[1], e[2], e[3], e[4], e[5], e[6], e[7], e[8], e[9], e[10], e[11], e[12], e[13], e[14], e[15]), this.finalTransform.opacity *= t.opacity, this.finalTransform.opMdf = t.opMdf ? !0 : this.finalTransform.opMdf, this.finalTransform.matMdf = t.matMdf ? !0 : this.finalTransform.matMdf), this.data.hasMask && (this.globalData.renderer.save(!0), this.maskManager.renderFrame(0 === this.data.ty ? null : r)), this.data.hd && (this.isVisible = !1), this.isVisible }, CVBaseElement.prototype.addMasks = function(t) { this.maskManager = new CVMaskElement(t, this, this.globalData) }, CVBaseElement.prototype.destroy = function() { this.canvasContext = null, this.data = null, this.globalData = null, this.maskManager && this.maskManager.destroy() }, CVBaseElement.prototype.mHelper = new Matrix, createElement(CVBaseElement, CVCompElement), CVCompElement.prototype.ctxTransform = CanvasRenderer.prototype.ctxTransform, CVCompElement.prototype.ctxOpacity = CanvasRenderer.prototype.ctxOpacity, CVCompElement.prototype.save = CanvasRenderer.prototype.save, CVCompElement.prototype.restore = CanvasRenderer.prototype.restore, CVCompElement.prototype.reset = function() { this.contextData.cArrPos = 0, this.contextData.cTr.reset(), this.contextData.cO = 1 }, CVCompElement.prototype.resize = function(t) { var e = Math.max(t.sx, t.sy);
        this.canvas.width = this.data.w * e, this.canvas.height = this.data.h * e, this.transformCanvas = { sc: e, w: this.data.w * e, h: this.data.h * e, props: [e, 0, 0, 0, 0, e, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1] }; var r, s = this.elements.length; for (r = 0; s > r; r += 1) this.elements[r] && 0 === this.elements[r].data.ty && this.elements[r].resize(t) }, CVCompElement.prototype.prepareFrame = function(t) { if (this.globalData.frameId = this.parentGlobalData.frameId, this.globalData.mdf = !1, this._parent.prepareFrame.call(this, t), this.isVisible !== !1 || this.data.xt) { var e = t;
            this.tm && (e = this.tm.v, e === this.data.op && (e = this.data.op - 1)), this.renderedFrame = e / this.data.sr; var r, s = this.elements.length; for (this.completeLayers || this.checkLayers(t), r = 0; s > r; r += 1)(this.completeLayers || this.elements[r]) && (this.elements[r].prepareFrame(e / this.data.sr - this.layers[r].st), 0 === this.elements[r].data.ty && this.elements[r].globalData.mdf && (this.globalData.mdf = !0));
            this.globalData.mdf && !this.data.xt && (this.canvasContext.clearRect(0, 0, this.data.w, this.data.h), this.ctxTransform(this.transformCanvas.props)) } }, CVCompElement.prototype.renderFrame = function(t) { if (this._parent.renderFrame.call(this, t) !== !1) { if (this.globalData.mdf) { var e, r = this.layers.length; for (e = r - 1; e >= 0; e -= 1)(this.completeLayers || this.elements[e]) && this.elements[e].renderFrame() }
            this.data.hasMask && this.globalData.renderer.restore(!0), this.firstFrame && (this.firstFrame = !1), this.parentGlobalData.renderer.save(), this.parentGlobalData.renderer.ctxTransform(this.finalTransform.mat.props), this.parentGlobalData.renderer.ctxOpacity(this.finalTransform.opacity), this.parentGlobalData.renderer.canvasContext.drawImage(this.canvas, 0, 0, this.data.w, this.data.h), this.parentGlobalData.renderer.restore(), this.globalData.mdf && this.reset() } }, CVCompElement.prototype.setElements = function(t) { this.elements = t }, CVCompElement.prototype.getElements = function() { return this.elements }, CVCompElement.prototype.destroy = function() { var t, e = this.layers.length; for (t = e - 1; t >= 0; t -= 1) this.elements[t].destroy();
        this.layers = null, this.elements = null, this._parent.destroy.call(this._parent) }, CVCompElement.prototype.checkLayers = CanvasRenderer.prototype.checkLayers, CVCompElement.prototype.buildItem = CanvasRenderer.prototype.buildItem, CVCompElement.prototype.checkPendingElements = CanvasRenderer.prototype.checkPendingElements, CVCompElement.prototype.addPendingElement = CanvasRenderer.prototype.addPendingElement, CVCompElement.prototype.buildAllItems = CanvasRenderer.prototype.buildAllItems, CVCompElement.prototype.createItem = CanvasRenderer.prototype.createItem, CVCompElement.prototype.createImage = CanvasRenderer.prototype.createImage, CVCompElement.prototype.createComp = CanvasRenderer.prototype.createComp, CVCompElement.prototype.createSolid = CanvasRenderer.prototype.createSolid, CVCompElement.prototype.createShape = CanvasRenderer.prototype.createShape, CVCompElement.prototype.createText = CanvasRenderer.prototype.createText, CVCompElement.prototype.createBase = CanvasRenderer.prototype.createBase, CVCompElement.prototype.buildElementParenting = CanvasRenderer.prototype.buildElementParenting, createElement(CVBaseElement, CVImageElement), CVImageElement.prototype.createElements = function() { var t = function() { if (this.globalData.elementLoaded(), this.assetData.w !== this.img.width || this.assetData.h !== this.img.height) { var t = document.createElement("canvas");
                    t.width = this.assetData.w, t.height = this.assetData.h; var e, r, s = t.getContext("2d"),
                        i = this.img.width,
                        a = this.img.height,
                        n = i / a,
                        o = this.assetData.w / this.assetData.h;
                    n > o ? (r = a, e = r * o) : (e = i, r = e / o), s.drawImage(this.img, (i - e) / 2, (a - r) / 2, e, r, 0, 0, this.assetData.w, this.assetData.h), this.img = t } }.bind(this),
            e = function() { this.failed = !0, this.globalData.elementLoaded() }.bind(this);
        this.img = new Image, this.img.addEventListener("load", t, !1), this.img.addEventListener("error", e, !1); var r = this.globalData.getAssetsPath(this.assetData);
        this.img.src = r, this._parent.createElements.call(this) }, CVImageElement.prototype.renderFrame = function(t) { if (!this.failed && this._parent.renderFrame.call(this, t) !== !1) { var e = this.canvasContext;
            this.globalData.renderer.save(); var r = this.finalTransform.mat.props;
            this.globalData.renderer.ctxTransform(r), this.globalData.renderer.ctxOpacity(this.finalTransform.opacity), e.drawImage(this.img, 0, 0), this.globalData.renderer.restore(this.data.hasMask), this.firstFrame && (this.firstFrame = !1) } }, CVImageElement.prototype.destroy = function() { this.img = null, this._parent.destroy.call(this._parent) }, CVMaskElement.prototype.getMaskProperty = function(t) { return this.viewData[t] }, CVMaskElement.prototype.prepareFrame = function(t) { var e, r = this.dynamicProperties.length; for (e = 0; r > e; e += 1) this.dynamicProperties[e].getValue(t), this.dynamicProperties[e].mdf && (this.element.globalData.mdf = !0) }, CVMaskElement.prototype.renderFrame = function(t) { var e, r, s, i, a, n = this.element.canvasContext,
            o = this.data.masksProperties.length,
            h = !1; for (e = 0; o > e; e++)
            if ("n" !== this.masksProperties[e].mode) { h === !1 && (n.beginPath(), h = !0), this.masksProperties[e].inv && (n.moveTo(0, 0), n.lineTo(this.element.globalData.compWidth, 0), n.lineTo(this.element.globalData.compWidth, this.element.globalData.compHeight), n.lineTo(0, this.element.globalData.compHeight), n.lineTo(0, 0)), a = this.viewData[e].v, r = t ? t.applyToPointArray(a.v[0][0], a.v[0][1], 0) : a.v[0], n.moveTo(r[0], r[1]); var l, p = a._length; for (l = 1; p > l; l++) r = t ? t.applyToPointArray(a.o[l - 1][0], a.o[l - 1][1], 0) : a.o[l - 1], s = t ? t.applyToPointArray(a.i[l][0], a.i[l][1], 0) : a.i[l], i = t ? t.applyToPointArray(a.v[l][0], a.v[l][1], 0) : a.v[l], n.bezierCurveTo(r[0], r[1], s[0], s[1], i[0], i[1]);
                r = t ? t.applyToPointArray(a.o[l - 1][0], a.o[l - 1][1], 0) : a.o[l - 1], s = t ? t.applyToPointArray(a.i[0][0], a.i[0][1], 0) : a.i[0], i = t ? t.applyToPointArray(a.v[0][0], a.v[0][1], 0) : a.v[0], n.bezierCurveTo(r[0], r[1], s[0], s[1], i[0], i[1]) }
        h && n.clip() }, CVMaskElement.prototype.getMask = function(t) { for (var e = 0, r = this.masksProperties.length; r > e;) { if (this.masksProperties[e].nm === t) return { maskPath: this.viewData[e].pv };
            e += 1 } }, CVMaskElement.prototype.destroy = function() { this.element = null }, createElement(CVBaseElement, CVShapeElement), CVShapeElement.prototype.lcEnum = { 1: "butt", 2: "round", 3: "butt" }, CVShapeElement.prototype.ljEnum = { 1: "miter", 2: "round", 3: "butt" }, CVShapeElement.prototype.transformHelper = { opacity: 1, mat: new Matrix, matMdf: !1, opMdf: !1 }, CVShapeElement.prototype.dashResetter = [], CVShapeElement.prototype.createElements = function() { this._parent.createElements.call(this), this.searchShapes(this.shapesData, this.viewData, this.dynamicProperties) }, CVShapeElement.prototype.searchShapes = function(t, e, r) { var s, i, a, n, o = t.length - 1,
            h = [],
            l = []; for (s = o; s >= 0; s -= 1)
            if ("fl" == t[s].ty || "st" == t[s].ty) { if (n = { type: t[s].ty, elements: [] }, e[s] = {}, ("fl" == t[s].ty || "st" == t[s].ty) && (e[s].c = PropertyFactory.getProp(this, t[s].c, 1, 255, r), e[s].c.k || (n.co = "rgb(" + bm_floor(e[s].c.v[0]) + "," + bm_floor(e[s].c.v[1]) + "," + bm_floor(e[s].c.v[2]) + ")")), e[s].o = PropertyFactory.getProp(this, t[s].o, 0, .01, r), "st" == t[s].ty) { if (n.lc = this.lcEnum[t[s].lc] || "round", n.lj = this.ljEnum[t[s].lj] || "round", 1 == t[s].lj && (n.ml = t[s].ml), e[s].w = PropertyFactory.getProp(this, t[s].w, 0, null, r), e[s].w.k || (n.wi = e[s].w.v), t[s].d) { var p = PropertyFactory.getDashProp(this, t[s].d, "canvas", r);
                        e[s].d = p, e[s].d.k || (n.da = e[s].d.dasharray, n["do"] = e[s].d.dashoffset) } } else n.r = 2 === t[s].r ? "evenodd" : "nonzero";
                this.stylesList.push(n), e[s].style = n, h.push(e[s].style) } else if ("gr" == t[s].ty) e[s] = { it: [] }, this.searchShapes(t[s].it, e[s].it, r);
        else if ("tr" == t[s].ty) e[s] = { transform: { mat: new Matrix, opacity: 1, matMdf: !1, opMdf: !1, op: PropertyFactory.getProp(this, t[s].o, 0, .01, r), mProps: PropertyFactory.getProp(this, t[s], 2, null, r) }, elements: [] };
        else if ("sh" == t[s].ty || "rc" == t[s].ty || "el" == t[s].ty || "sr" == t[s].ty) { e[s] = { nodes: [], trNodes: [], tr: [0, 0, 0, 0, 0, 0] }; var m = 4; "rc" == t[s].ty ? m = 5 : "el" == t[s].ty ? m = 6 : "sr" == t[s].ty && (m = 7), e[s].sh = ShapePropertyFactory.getShapeProp(this, t[s], m, r), this.shapes.push(e[s].sh), this.addShapeToModifiers(e[s]), a = this.stylesList.length; var f = !1,
                c = !1; for (i = 0; a > i; i += 1) this.stylesList[i].closed || (this.stylesList[i].elements.push(e[s]), "st" === this.stylesList[i].type ? f = !0 : c = !0);
            e[s].st = f, e[s].fl = c } else if ("tm" == t[s].ty || "rd" == t[s].ty || "rp" == t[s].ty) { var d = ShapeModifiers.getModifier(t[s].ty);
            d.init(this, t[s], r), this.shapeModifiers.push(d), l.push(d), e[s] = d } for (o = h.length, s = 0; o > s; s += 1) h[s].closed = !0; for (o = l.length, s = 0; o > s; s += 1) l[s].closed = !0 }, CVShapeElement.prototype.addShapeToModifiers = IShapeElement.prototype.addShapeToModifiers, CVShapeElement.prototype.renderModifiers = IShapeElement.prototype.renderModifiers, CVShapeElement.prototype.renderFrame = function(t) { this._parent.renderFrame.call(this, t) !== !1 && (this.transformHelper.mat.reset(), this.transformHelper.opacity = this.finalTransform.opacity, this.transformHelper.matMdf = !1, this.transformHelper.opMdf = this.finalTransform.opMdf, this.renderModifiers(), this.renderShape(this.transformHelper, null, null, !0), this.data.hasMask && this.globalData.renderer.restore(!0)) }, CVShapeElement.prototype.renderShape = function(t, e, r, s) { var i, a; if (!e)
            for (e = this.shapesData, a = this.stylesList.length, i = 0; a > i; i += 1) this.stylesList[i].d = "", this.stylesList[i].mdf = !1;
        r || (r = this.viewData), a = e.length - 1; var n, o; for (n = t, i = a; i >= 0; i -= 1)
            if ("tr" == e[i].ty) { n = r[i].transform; var h = r[i].transform.mProps.v.props; if (n.matMdf = n.mProps.mdf, n.opMdf = n.op.mdf, o = n.mat, o.cloneFromProps(h), t) { var l = t.mat.props;
                    n.opacity = t.opacity, n.opacity *= r[i].transform.op.v, n.matMdf = t.matMdf ? !0 : n.matMdf, n.opMdf = t.opMdf ? !0 : n.opMdf, o.transform(l[0], l[1], l[2], l[3], l[4], l[5], l[6], l[7], l[8], l[9], l[10], l[11], l[12], l[13], l[14], l[15]) } else n.opacity = n.op.o } else "sh" == e[i].ty || "el" == e[i].ty || "rc" == e[i].ty || "sr" == e[i].ty ? this.renderPath(e[i], r[i], n) : "fl" == e[i].ty ? this.renderFill(e[i], r[i], n) : "st" == e[i].ty ? this.renderStroke(e[i], r[i], n) : "gr" == e[i].ty ? this.renderShape(n, e[i].it, r[i].it) : "tm" == e[i].ty;
        if (s) { a = this.stylesList.length; var p, m, f, c, d, u, y, g = this.globalData.renderer,
                v = this.globalData.canvasContext; for (g.save(), g.ctxTransform(this.finalTransform.mat.props), i = 0; a > i; i += 1)
                if (y = this.stylesList[i].type, "st" !== y || 0 !== this.stylesList[i].wi) { for (g.save(), d = this.stylesList[i].elements, "st" === y ? (v.strokeStyle = this.stylesList[i].co, v.lineWidth = this.stylesList[i].wi, v.lineCap = this.stylesList[i].lc, v.lineJoin = this.stylesList[i].lj, v.miterLimit = this.stylesList[i].ml || 0) : v.fillStyle = this.stylesList[i].co, g.ctxOpacity(this.stylesList[i].coOp), "st" !== y && v.beginPath(), m = d.length, p = 0; m > p; p += 1) { for ("st" === y && (v.beginPath(), this.stylesList[i].da ? (v.setLineDash(this.stylesList[i].da), v.lineDashOffset = this.stylesList[i]["do"], this.globalData.isDashed = !0) : this.globalData.isDashed && (v.setLineDash(this.dashResetter), this.globalData.isDashed = !1)), u = d[p].trNodes, c = u.length, f = 0; c > f; f += 1) "m" == u[f].t ? v.moveTo(u[f].p[0], u[f].p[1]) : "c" == u[f].t ? v.bezierCurveTo(u[f].p1[0], u[f].p1[1], u[f].p2[0], u[f].p2[1], u[f].p3[0], u[f].p3[1]) : v.closePath(); "st" === y && v.stroke() } "st" !== y && v.fill(this.stylesList[i].r), g.restore() }
            g.restore(), this.firstFrame && (this.firstFrame = !1) } }, CVShapeElement.prototype.renderPath = function(t, e, r) { var s, i, a, n, o = r.matMdf || e.sh.mdf || this.firstFrame; if (o) { var h = e.sh.paths;
            n = h._length; var l = e.trNodes; for (l.length = 0, a = 0; n > a; a += 1) { var p = h.shapes[a]; if (p && p.v) { for (s = p._length, i = 1; s > i; i += 1) 1 == i && l.push({ t: "m", p: r.mat.applyToPointArray(p.v[0][0], p.v[0][1], 0) }), l.push({ t: "c", p1: r.mat.applyToPointArray(p.o[i - 1][0], p.o[i - 1][1], 0), p2: r.mat.applyToPointArray(p.i[i][0], p.i[i][1], 0), p3: r.mat.applyToPointArray(p.v[i][0], p.v[i][1], 0) });
                    1 == s && l.push({ t: "m", p: r.mat.applyToPointArray(p.v[0][0], p.v[0][1], 0) }), p.c && s && (l.push({ t: "c", p1: r.mat.applyToPointArray(p.o[i - 1][0], p.o[i - 1][1], 0), p2: r.mat.applyToPointArray(p.i[0][0], p.i[0][1], 0), p3: r.mat.applyToPointArray(p.v[0][0], p.v[0][1], 0) }), l.push({ t: "z" })), e.lStr = l } } if (e.st)
                for (i = 0; 16 > i; i += 1) e.tr[i] = r.mat.props[i];
            e.trNodes = l } }, CVShapeElement.prototype.renderFill = function(t, e, r) { var s = e.style;
        (e.c.mdf || this.firstFrame) && (s.co = "rgb(" + bm_floor(e.c.v[0]) + "," + bm_floor(e.c.v[1]) + "," + bm_floor(e.c.v[2]) + ")"), (e.o.mdf || r.opMdf || this.firstFrame) && (s.coOp = e.o.v * r.opacity) }, CVShapeElement.prototype.renderStroke = function(t, e, r) { var s = e.style,
            i = e.d;
        i && (i.mdf || this.firstFrame) && (s.da = i.dasharray, s["do"] = i.dashoffset), (e.c.mdf || this.firstFrame) && (s.co = "rgb(" + bm_floor(e.c.v[0]) + "," + bm_floor(e.c.v[1]) + "," + bm_floor(e.c.v[2]) + ")"), (e.o.mdf || r.opMdf || this.firstFrame) && (s.coOp = e.o.v * r.opacity), (e.w.mdf || this.firstFrame) && (s.wi = e.w.v) }, CVShapeElement.prototype.destroy = function() { this.shapesData = null, this.globalData = null, this.canvasContext = null, this.stylesList.length = 0, this.viewData.length = 0, this._parent.destroy.call(this._parent) }, createElement(CVBaseElement, CVSolidElement), CVSolidElement.prototype.renderFrame = function(t) { if (this._parent.renderFrame.call(this, t) !== !1) { var e = this.canvasContext;
            this.globalData.renderer.save(), this.globalData.renderer.ctxTransform(this.finalTransform.mat.props), this.globalData.renderer.ctxOpacity(this.finalTransform.opacity), e.fillStyle = this.data.sc, e.fillRect(0, 0, this.data.sw, this.data.sh), this.globalData.renderer.restore(this.data.hasMask), this.firstFrame && (this.firstFrame = !1) } }, createElement(CVBaseElement, CVTextElement), CVTextElement.prototype.init = ITextElement.prototype.init, CVTextElement.prototype.getMeasures = ITextElement.prototype.getMeasures, CVTextElement.prototype.getMult = ITextElement.prototype.getMult, CVTextElement.prototype.prepareFrame = ITextElement.prototype.prepareFrame, CVTextElement.prototype.tHelper = document.createElement("canvas").getContext("2d"), CVTextElement.prototype.createElements = function() { this._parent.createElements.call(this) }, CVTextElement.prototype.buildNewText = function() { var t = this.currentTextDocumentData;
        this.renderedLetters = Array.apply(null, { length: this.currentTextDocumentData.l ? this.currentTextDocumentData.l.length : 0 }); var e = !1;
        t.fc ? (e = !0, this.values.fill = "rgb(" + Math.round(255 * t.fc[0]) + "," + Math.round(255 * t.fc[1]) + "," + Math.round(255 * t.fc[2]) + ")") : this.values.fill = "rgba(0,0,0,0)", this.fill = e; var r = !1;
        t.sc && (r = !0, this.values.stroke = "rgb(" + Math.round(255 * t.sc[0]) + "," + Math.round(255 * t.sc[1]) + "," + Math.round(255 * t.sc[2]) + ")", this.values.sWidth = t.sw); var s, i, a = this.globalData.fontManager.getFontByName(t.f),
            n = t.l,
            o = this.mHelper;
        this.stroke = r, this.values.fValue = t.s + "px " + this.globalData.fontManager.getFontByName(t.f).fFamily, i = t.t.length, this.tHelper.font = this.values.fValue; var h, l, p, m, f, c, d, u, y, g, v = this.data.singleShape; if (v) var b = 0,
            E = 0,
            P = t.lineWidths,
            x = t.boxWidth,
            S = !0; var C = 0; for (s = 0; i > s; s += 1) { h = this.globalData.fontManager.getCharData(t.t.charAt(s), a.fStyle, this.globalData.fontManager.getFontByName(t.f).fFamily); var l; if (l = h ? h.data : null, o.reset(), v && n[s].n && (b = 0, E += t.yOffset, E += S ? 1 : 0, S = !1), l && l.shapes) { if (f = l.shapes[0].it, d = f.length, o.scale(t.s / 100, t.s / 100), v) { switch (t.ps && o.translate(t.ps[0], t.ps[1] + t.ascent, 0), t.j) {
                        case 1:
                            o.translate(t.justifyOffset + (x - P[n[s].line]), 0, 0); break;
                        case 2:
                            o.translate(t.justifyOffset + (x - P[n[s].line]) / 2, 0, 0) }
                    o.translate(b, E, 0) } for (y = new Array(d), c = 0; d > c; c += 1) { for (m = f[c].ks.k.i.length, u = f[c].ks.k, g = [], p = 1; m > p; p += 1) 1 == p && g.push(o.applyToX(u.v[0][0], u.v[0][1], 0), o.applyToY(u.v[0][0], u.v[0][1], 0)), g.push(o.applyToX(u.o[p - 1][0], u.o[p - 1][1], 0), o.applyToY(u.o[p - 1][0], u.o[p - 1][1], 0), o.applyToX(u.i[p][0], u.i[p][1], 0), o.applyToY(u.i[p][0], u.i[p][1], 0), o.applyToX(u.v[p][0], u.v[p][1], 0), o.applyToY(u.v[p][0], u.v[p][1], 0));
                    g.push(o.applyToX(u.o[p - 1][0], u.o[p - 1][1], 0), o.applyToY(u.o[p - 1][0], u.o[p - 1][1], 0), o.applyToX(u.i[0][0], u.i[0][1], 0), o.applyToY(u.i[0][0], u.i[0][1], 0), o.applyToX(u.v[0][0], u.v[0][1], 0), o.applyToY(u.v[0][0], u.v[0][1], 0)), y[c] = g } } else y = [];
            v && (b += n[s].l), this.textSpans[C] ? this.textSpans[C].elem = y : this.textSpans[C] = { elem: y }, C += 1 } }, CVTextElement.prototype.renderFrame = function(t) { if (this._parent.renderFrame.call(this, t) !== !1) { var e = this.canvasContext,
                r = this.finalTransform.mat.props;
            this.globalData.renderer.save(), this.globalData.renderer.ctxTransform(r), this.globalData.renderer.ctxOpacity(this.finalTransform.opacity), e.font = this.values.fValue, e.lineCap = "butt", e.lineJoin = "miter", e.miterLimit = 4, this.data.singleShape || this.getMeasures(); var s, i, a, n, o, h, l = this.renderedLetters,
                p = this.currentTextDocumentData.l;
            i = p.length; var m, f, c, d = null,
                u = null,
                y = null; for (s = 0; i > s; s += 1)
                if (!p[s].n) { if (m = l[s], m && (this.globalData.renderer.save(), this.globalData.renderer.ctxTransform(m.props), this.globalData.renderer.ctxOpacity(m.o)), this.fill) { for (m && m.fc ? d !== m.fc && (d = m.fc, e.fillStyle = m.fc) : d !== this.values.fill && (d = this.values.fill, e.fillStyle = this.values.fill), f = this.textSpans[s].elem, n = f.length, this.globalData.canvasContext.beginPath(), a = 0; n > a; a += 1)
                            for (c = f[a], h = c.length, this.globalData.canvasContext.moveTo(c[0], c[1]), o = 2; h > o; o += 6) this.globalData.canvasContext.bezierCurveTo(c[o], c[o + 1], c[o + 2], c[o + 3], c[o + 4], c[o + 5]);
                        this.globalData.canvasContext.closePath(), this.globalData.canvasContext.fill() } if (this.stroke) { for (m && m.sw ? y !== m.sw && (y = m.sw, e.lineWidth = m.sw) : y !== this.values.sWidth && (y = this.values.sWidth, e.lineWidth = this.values.sWidth), m && m.sc ? u !== m.sc && (u = m.sc, e.strokeStyle = m.sc) : u !== this.values.stroke && (u = this.values.stroke, e.strokeStyle = this.values.stroke), f = this.textSpans[s].elem, n = f.length, this.globalData.canvasContext.beginPath(), a = 0; n > a; a += 1)
                            for (c = f[a], h = c.length, this.globalData.canvasContext.moveTo(c[0], c[1]), o = 2; h > o; o += 6) this.globalData.canvasContext.bezierCurveTo(c[o], c[o + 1], c[o + 2], c[o + 3], c[o + 4], c[o + 5]);
                        this.globalData.canvasContext.closePath(), this.globalData.canvasContext.stroke() }
                    m && this.globalData.renderer.restore() }
            this.globalData.renderer.restore(this.data.hasMask), this.firstFrame && (this.firstFrame = !1) } }, createElement(BaseElement, HBaseElement), HBaseElement.prototype.checkBlendMode = function() {}, HBaseElement.prototype.setBlendMode = BaseElement.prototype.setBlendMode, HBaseElement.prototype.getBaseElement = function() { return this.baseElement }, HBaseElement.prototype.createElements = function() { this.data.hasMask ? (this.layerElement = document.createElementNS(svgNS, "svg"), styleDiv(this.layerElement), this.baseElement = this.layerElement, this.maskedElement = this.layerElement) : this.layerElement = this.parentContainer, this.transformedElement = this.layerElement, !this.data.ln || 4 !== this.data.ty && 0 !== this.data.ty || (this.layerElement === this.parentContainer && (this.layerElement = document.createElementNS(svgNS, "g"), this.baseElement = this.layerElement), this.layerElement.setAttribute("id", this.data.ln)), this.setBlendMode(), this.layerElement !== this.parentContainer && (this.placeholder = null), this.checkParenting() }, HBaseElement.prototype.renderFrame = function(t) { if (3 === this.data.ty) return !1; if (this.currentFrameNum === this.lastNum || !this.isVisible) return this.isVisible;
        this.lastNum = this.currentFrameNum, this.finalTransform.opMdf = this.finalTransform.op.mdf, this.finalTransform.matMdf = this.finalTransform.mProp.mdf, this.finalTransform.opacity = this.finalTransform.op.v, this.firstFrame && (this.finalTransform.opMdf = !0, this.finalTransform.matMdf = !0); var e, r = this.finalTransform.mat; if (this.hierarchy) { var s, i = this.hierarchy.length; for (e = this.finalTransform.mProp.v.props, r.cloneFromProps(e), s = 0; i > s; s += 1) this.finalTransform.matMdf = this.hierarchy[s].finalTransform.mProp.mdf ? !0 : this.finalTransform.matMdf, e = this.hierarchy[s].finalTransform.mProp.v.props, r.transform(e[0], e[1], e[2], e[3], e[4], e[5], e[6], e[7], e[8], e[9], e[10], e[11], e[12], e[13], e[14], e[15]) } else this.isVisible && this.finalTransform.matMdf && (t ? (e = this.finalTransform.mProp.v.props, r.cloneFromProps(e)) : r.cloneFromProps(this.finalTransform.mProp.v.props)); return this.data.hasMask && this.maskManager.renderFrame(r), t && (e = t.mat.props, r.cloneFromProps(e), this.finalTransform.opacity *= t.opacity, this.finalTransform.opMdf = t.opMdf ? !0 : this.finalTransform.opMdf, this.finalTransform.matMdf = t.matMdf ? !0 : this.finalTransform.matMdf), this.finalTransform.matMdf && (this.transformedElement.style.transform = this.transformedElement.style.webkitTransform = r.toCSS(), this.finalMat = r), this.finalTransform.opMdf && (this.transformedElement.style.opacity = this.finalTransform.opacity), this.isVisible }, HBaseElement.prototype.destroy = function() { this.layerElement = null, this.transformedElement = null, this.parentContainer = null, this.matteElement && (this.matteElement = null), this.maskManager && (this.maskManager.destroy(), this.maskManager = null) }, HBaseElement.prototype.getDomElement = function() { return this.layerElement }, HBaseElement.prototype.addMasks = function(t) { this.maskManager = new MaskElement(t, this, this.globalData) }, HBaseElement.prototype.hide = function() {}, HBaseElement.prototype.setMatte = function() {}, HBaseElement.prototype.buildElementParenting = HybridRenderer.prototype.buildElementParenting, createElement(HBaseElement, HSolidElement), HSolidElement.prototype.createElements = function() { var t = document.createElement("div");
        styleDiv(t); var e = document.createElementNS(svgNS, "svg");
        styleDiv(e), e.setAttribute("width", this.data.sw), e.setAttribute("height", this.data.sh), t.appendChild(e), this.layerElement = t, this.transformedElement = t, this.baseElement = t, this.innerElem = t, this.data.ln && this.innerElem.setAttribute("id", this.data.ln), 0 !== this.data.bm && this.setBlendMode(); var r = document.createElementNS(svgNS, "rect");
        r.setAttribute("width", this.data.sw), r.setAttribute("height", this.data.sh), r.setAttribute("fill", this.data.sc), e.appendChild(r), this.data.hasMask && (this.maskedElement = r), this.checkParenting() }, HSolidElement.prototype.hide = IImageElement.prototype.hide, HSolidElement.prototype.renderFrame = IImageElement.prototype.renderFrame, HSolidElement.prototype.destroy = IImageElement.prototype.destroy, createElement(HBaseElement, HCompElement), HCompElement.prototype.createElements = function() {
        var t = document.createElement("div");
        if (styleDiv(t), this.data.ln && t.setAttribute("id", this.data.ln), t.style.clip = "rect(0px, " + this.data.w + "px, " + this.data.h + "px, 0px)",
            this.data.hasMask) { var e = document.createElementNS(svgNS, "svg");
            styleDiv(e), e.setAttribute("width", this.data.w), e.setAttribute("height", this.data.h); var r = document.createElementNS(svgNS, "g");
            e.appendChild(r), t.appendChild(e), this.maskedElement = r, this.baseElement = t, this.layerElement = r, this.transformedElement = t } else this.layerElement = t, this.baseElement = this.layerElement, this.transformedElement = t;
        this.checkParenting()
    }, HCompElement.prototype.hide = ICompElement.prototype.hide, HCompElement.prototype.prepareFrame = ICompElement.prototype.prepareFrame, HCompElement.prototype.setElements = ICompElement.prototype.setElements, HCompElement.prototype.getElements = ICompElement.prototype.getElements, HCompElement.prototype.destroy = ICompElement.prototype.destroy, HCompElement.prototype.renderFrame = function(t) { var e, r = this._parent.renderFrame.call(this, t),
            s = this.layers.length; if (r === !1) return void this.hide(); for (this.hidden = !1, e = 0; s > e; e += 1)(this.completeLayers || this.elements[e]) && this.elements[e].renderFrame();
        this.firstFrame && (this.firstFrame = !1) }, HCompElement.prototype.checkLayers = BaseRenderer.prototype.checkLayers, HCompElement.prototype.buildItem = HybridRenderer.prototype.buildItem, HCompElement.prototype.checkPendingElements = HybridRenderer.prototype.checkPendingElements, HCompElement.prototype.addPendingElement = HybridRenderer.prototype.addPendingElement, HCompElement.prototype.buildAllItems = BaseRenderer.prototype.buildAllItems, HCompElement.prototype.createItem = HybridRenderer.prototype.createItem, HCompElement.prototype.buildElementParenting = HybridRenderer.prototype.buildElementParenting, HCompElement.prototype.createImage = HybridRenderer.prototype.createImage, HCompElement.prototype.createComp = HybridRenderer.prototype.createComp, HCompElement.prototype.createSolid = HybridRenderer.prototype.createSolid, HCompElement.prototype.createShape = HybridRenderer.prototype.createShape, HCompElement.prototype.createText = HybridRenderer.prototype.createText, HCompElement.prototype.createBase = HybridRenderer.prototype.createBase, HCompElement.prototype.appendElementInPos = HybridRenderer.prototype.appendElementInPos, createElement(HBaseElement, HShapeElement);
    var parent = HShapeElement.prototype._parent;
    extendPrototype(IShapeElement, HShapeElement), HShapeElement.prototype._parent = parent, HShapeElement.prototype.createElements = function() { var t = document.createElement("div");
        styleDiv(t); var e = document.createElementNS(svgNS, "svg");
        styleDiv(e); var r = this.comp.data ? this.comp.data : this.globalData.compSize; if (e.setAttribute("width", r.w), e.setAttribute("height", r.h), this.data.hasMask) { var s = document.createElementNS(svgNS, "g");
            t.appendChild(e), e.appendChild(s), this.maskedElement = s, this.layerElement = s, this.shapesContainer = s } else t.appendChild(e), this.layerElement = e, this.shapesContainer = document.createElementNS(svgNS, "g"), this.layerElement.appendChild(this.shapesContainer);
        this.data.hd || (this.baseElement = t), this.innerElem = t, this.data.ln && this.innerElem.setAttribute("id", this.data.ln), this.searchShapes(this.shapesData, this.viewData, this.layerElement, this.dynamicProperties, 0), this.buildExpressionInterface(), this.layerElement = t, this.transformedElement = t, this.shapeCont = e, 0 !== this.data.bm && this.setBlendMode(), this.checkParenting() }, HShapeElement.prototype.renderFrame = function(t) { var e = this._parent.renderFrame.call(this, t); if (e === !1) return void this.hide(); if (this.hidden && (this.layerElement.style.display = "block", this.hidden = !1), this.renderModifiers(), this.addedTransforms.mdf = this.finalTransform.matMdf, this.addedTransforms.mats.length = 1, this.addedTransforms.mats[0] = this.finalTransform.mat, this.renderShape(null, null, !0, null), this.isVisible && (this.elemMdf || this.firstFrame)) { var r = this.shapeCont.getBBox(),
                s = !1;
            this.currentBBox.w !== r.width && (this.currentBBox.w = r.width, this.shapeCont.setAttribute("width", r.width), s = !0), this.currentBBox.h !== r.height && (this.currentBBox.h = r.height, this.shapeCont.setAttribute("height", r.height), s = !0), (s || this.currentBBox.x !== r.x || this.currentBBox.y !== r.y) && (this.currentBBox.w = r.width, this.currentBBox.h = r.height, this.currentBBox.x = r.x, this.currentBBox.y = r.y, this.shapeCont.setAttribute("viewBox", this.currentBBox.x + " " + this.currentBBox.y + " " + this.currentBBox.w + " " + this.currentBBox.h), this.shapeCont.style.transform = this.shapeCont.style.webkitTransform = "translate(" + this.currentBBox.x + "px," + this.currentBBox.y + "px)") } }, createElement(HBaseElement, HTextElement), HTextElement.prototype.init = ITextElement.prototype.init, HTextElement.prototype.getMeasures = ITextElement.prototype.getMeasures, HTextElement.prototype.createPathShape = ITextElement.prototype.createPathShape, HTextElement.prototype.prepareFrame = ITextElement.prototype.prepareFrame, HTextElement.prototype.createElements = function() { this.isMasked = this.checkMasks(); var t = document.createElement("div"); if (styleDiv(t), this.layerElement = t, this.transformedElement = t, this.isMasked) { this.renderType = "svg"; var e = document.createElementNS(svgNS, "svg");
            styleDiv(e), this.cont = e, this.compW = this.comp.data.w, this.compH = this.comp.data.h, e.setAttribute("width", this.compW), e.setAttribute("height", this.compH); var r = document.createElementNS(svgNS, "g");
            e.appendChild(r), t.appendChild(e), this.maskedElement = r, this.innerElem = r } else this.renderType = "html", this.innerElem = t;
        this.baseElement = t, this.checkParenting() }, HTextElement.prototype.buildNewText = function() { var t = this.currentTextDocumentData;
        this.renderedLetters = Array.apply(null, { length: this.currentTextDocumentData.l ? this.currentTextDocumentData.l.length : 0 }), this.innerElem.style.color = this.innerElem.style.fill = t.fc ? "rgb(" + Math.round(255 * t.fc[0]) + "," + Math.round(255 * t.fc[1]) + "," + Math.round(255 * t.fc[2]) + ")" : "rgba(0,0,0,0)", t.sc && (this.innerElem.style.stroke = "rgb(" + Math.round(255 * t.sc[0]) + "," + Math.round(255 * t.sc[1]) + "," + Math.round(255 * t.sc[2]) + ")", this.innerElem.style.strokeWidth = t.sw + "px"); var e = this.globalData.fontManager.getFontByName(t.f); if (!this.globalData.fontManager.chars)
            if (this.innerElem.style.fontSize = t.s + "px", this.innerElem.style.lineHeight = t.s + "px", e.fClass) this.innerElem.className = e.fClass;
            else { this.innerElem.style.fontFamily = e.fFamily; var r = t.fWeight,
                    s = t.fStyle;
                this.innerElem.style.fontStyle = s, this.innerElem.style.fontWeight = r }
        var i, a, n = t.l;
        a = n.length; var o, h, l, p, m = this.mHelper,
            f = "",
            c = 0; for (i = 0; a > i; i += 1) { if (this.globalData.fontManager.chars ? (this.textPaths[c] ? o = this.textPaths[c] : (o = document.createElementNS(svgNS, "path"), o.setAttribute("stroke-linecap", "butt"), o.setAttribute("stroke-linejoin", "round"), o.setAttribute("stroke-miterlimit", "4")), this.isMasked || (this.textSpans[c] ? (h = this.textSpans[c], l = h.children[0]) : (h = document.createElement("div"), l = document.createElementNS(svgNS, "svg"), l.appendChild(o), styleDiv(h)))) : this.isMasked ? o = this.textPaths[c] ? this.textPaths[c] : document.createElementNS(svgNS, "text") : this.textSpans[c] ? (h = this.textSpans[c], o = this.textPaths[c]) : (h = document.createElement("span"), styleDiv(h), o = document.createElement("span"), styleDiv(o), h.appendChild(o)), this.globalData.fontManager.chars) { var d, u = this.globalData.fontManager.getCharData(t.t.charAt(i), e.fStyle, this.globalData.fontManager.getFontByName(t.f).fFamily); if (d = u ? u.data : null, m.reset(), d && d.shapes && (p = d.shapes[0].it, m.scale(t.s / 100, t.s / 100), f = this.createPathShape(m, p), o.setAttribute("d", f)), this.isMasked) this.innerElem.appendChild(o);
                else if (this.innerElem.appendChild(h), d && d.shapes) { document.body.appendChild(l); var y = l.getBBox();
                    l.setAttribute("width", y.width), l.setAttribute("height", y.height), l.setAttribute("viewBox", y.x + " " + y.y + " " + y.width + " " + y.height), l.style.transform = l.style.webkitTransform = "translate(" + y.x + "px," + y.y + "px)", n[i].yOffset = y.y, h.appendChild(l) } else l.setAttribute("width", 1), l.setAttribute("height", 1) } else o.textContent = n[i].val, o.setAttributeNS("http://www.w3.org/XML/1998/namespace", "xml:space", "preserve"), this.isMasked ? this.innerElem.appendChild(o) : (this.innerElem.appendChild(h), o.style.transform = o.style.webkitTransform = "translate3d(0," + -t.s / 1.2 + "px,0)");
            this.textSpans[c] = this.isMasked ? o : h, this.textSpans[c].style.display = "block", this.textPaths[c] = o, c += 1 } for (; c < this.textSpans.length;) this.textSpans[c].style.display = "none", c += 1 }, HTextElement.prototype.hide = SVGTextElement.prototype.hide, HTextElement.prototype.renderFrame = function(t) { var e = this._parent.renderFrame.call(this, t); if (e === !1) return void this.hide(); if (this.hidden && (this.hidden = !1, this.innerElem.style.display = "block", this.layerElement.style.display = "block"), this.data.singleShape) { if (!this.firstFrame && !this.lettersChangedFlag) return;
            this.isMasked && this.finalTransform.matMdf && (this.cont.setAttribute("viewBox", -this.finalTransform.mProp.p.v[0] + " " + -this.finalTransform.mProp.p.v[1] + " " + this.compW + " " + this.compH), this.cont.style.transform = this.cont.style.webkitTransform = "translate(" + -this.finalTransform.mProp.p.v[0] + "px," + -this.finalTransform.mProp.p.v[1] + "px)") } if (this.getMeasures(), this.lettersChangedFlag) { var r, s, i = this.renderedLetters,
                a = this.currentTextDocumentData.l;
            s = a.length; var n; for (r = 0; s > r; r += 1) a[r].n || (n = i[r], this.isMasked ? this.textSpans[r].setAttribute("transform", n.m) : this.textSpans[r].style.transform = this.textSpans[r].style.webkitTransform = n.m, this.textSpans[r].style.opacity = n.o, n.sw && this.textPaths[r].setAttribute("stroke-width", n.sw), n.sc && this.textPaths[r].setAttribute("stroke", n.sc), n.fc && (this.textPaths[r].setAttribute("fill", n.fc), this.textPaths[r].style.color = n.fc)); if (this.isVisible && (this.elemMdf || this.firstFrame) && this.innerElem.getBBox) { var o = this.innerElem.getBBox();
                this.currentBBox.w !== o.width && (this.currentBBox.w = o.width, this.cont.setAttribute("width", o.width)), this.currentBBox.h !== o.height && (this.currentBBox.h = o.height, this.cont.setAttribute("height", o.height)), (this.currentBBox.w !== o.width || this.currentBBox.h !== o.height || this.currentBBox.x !== o.x || this.currentBBox.y !== o.y) && (this.currentBBox.w = o.width, this.currentBBox.h = o.height, this.currentBBox.x = o.x, this.currentBBox.y = o.y, this.cont.setAttribute("viewBox", this.currentBBox.x + " " + this.currentBBox.y + " " + this.currentBBox.w + " " + this.currentBBox.h), this.cont.style.transform = this.cont.style.webkitTransform = "translate(" + this.currentBBox.x + "px," + this.currentBBox.y + "px)") }
            this.firstFrame && (this.firstFrame = !1) } }, HTextElement.prototype.destroy = SVGTextElement.prototype.destroy, createElement(HBaseElement, HImageElement), HImageElement.prototype.createElements = function() { var t = this.globalData.getAssetsPath(this.assetData),
            e = new Image; if (this.data.hasMask) { var r = document.createElement("div");
            styleDiv(r); var s = document.createElementNS(svgNS, "svg");
            styleDiv(s), s.setAttribute("width", this.assetData.w), s.setAttribute("height", this.assetData.h), r.appendChild(s), this.imageElem = document.createElementNS(svgNS, "image"), this.imageElem.setAttribute("width", this.assetData.w + "px"), this.imageElem.setAttribute("height", this.assetData.h + "px"), this.imageElem.setAttributeNS("http://www.w3.org/1999/xlink", "href", t), s.appendChild(this.imageElem), this.layerElement = r, this.transformedElement = r, this.baseElement = r, this.innerElem = r, this.maskedElement = this.imageElem } else styleDiv(e), this.layerElement = e, this.baseElement = e, this.innerElem = e, this.transformedElement = e;
        e.src = t, this.data.ln && this.innerElem.setAttribute("id", this.data.ln), this.checkParenting() }, HImageElement.prototype.hide = HSolidElement.prototype.hide, HImageElement.prototype.renderFrame = HSolidElement.prototype.renderFrame, HImageElement.prototype.destroy = HSolidElement.prototype.destroy, createElement(HBaseElement, HCameraElement), HCameraElement.prototype.setup = function() { var t, e, r = this.comp.threeDElements.length; for (t = 0; r > t; t += 1) e = this.comp.threeDElements[t], e.perspectiveElem.style.perspective = e.perspectiveElem.style.webkitPerspective = this.pe.v + "px", e.container.style.transformOrigin = e.container.style.mozTransformOrigin = e.container.style.webkitTransformOrigin = "0px 0px 0px", e.perspectiveElem.style.transform = e.perspectiveElem.style.webkitTransform = "matrix3d(1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1)" }, HCameraElement.prototype.createElements = function() {}, HCameraElement.prototype.hide = function() {}, HCameraElement.prototype.renderFrame = function() { var t, e, r = this.firstFrame; if (this.hierarchy)
            for (e = this.hierarchy.length, t = 0; e > t; t += 1) r = this.hierarchy[t].finalTransform.mProp.mdf ? !0 : r; if (r || this.p && this.p.mdf || this.px && (this.px.mdf || this.py.mdf || this.pz.mdf) || this.rx.mdf || this.ry.mdf || this.rz.mdf || this.or.mdf || this.a && this.a.mdf) { if (this.mat.reset(), this.p ? this.mat.translate(-this.p.v[0], -this.p.v[1], this.p.v[2]) : this.mat.translate(-this.px.v, -this.py.v, this.pz.v), this.a) { var s = [this.p.v[0] - this.a.v[0], this.p.v[1] - this.a.v[1], this.p.v[2] - this.a.v[2]],
                    i = Math.sqrt(Math.pow(s[0], 2) + Math.pow(s[1], 2) + Math.pow(s[2], 2)),
                    a = [s[0] / i, s[1] / i, s[2] / i],
                    n = Math.sqrt(a[2] * a[2] + a[0] * a[0]),
                    o = Math.atan2(a[1], n),
                    h = Math.atan2(a[0], -a[2]);
                this.mat.rotateY(h).rotateX(-o) } if (this.mat.rotateX(-this.rx.v).rotateY(-this.ry.v).rotateZ(this.rz.v), this.mat.rotateX(-this.or.v[0]).rotateY(-this.or.v[1]).rotateZ(this.or.v[2]), this.mat.translate(this.globalData.compSize.w / 2, this.globalData.compSize.h / 2, 0), this.mat.translate(0, 0, this.pe.v), this.hierarchy) { var l; for (e = this.hierarchy.length, t = 0; e > t; t += 1) l = this.hierarchy[t].finalTransform.mProp.iv.props, this.mat.transform(l[0], l[1], l[2], l[3], l[4], l[5], l[6], l[7], l[8], l[9], l[10], l[11], -l[12], -l[13], l[14], l[15]) }
            e = this.comp.threeDElements.length; var p; for (t = 0; e > t; t += 1) p = this.comp.threeDElements[t], p.container.style.transform = p.container.style.webkitTransform = this.mat.toCSS() }
        this.firstFrame = !1 }, HCameraElement.prototype.destroy = function() {};
    var Expressions = function() {
        function t(t) { t.renderer.compInterface = CompExpressionInterface(t.renderer), t.renderer.globalData.projectInterface.registerComposition(t.renderer) } var e = {}; return e.initExpressions = t, e }();
    expressionsPlugin = Expressions,
        function() {
            function t() { return this.pv }

            function e(t, e) { t *= this.elem.globalData.frameRate; var r, s, i = 0,
                    a = this.keyframes.length - 1,
                    n = 1,
                    o = !0;
                e = void 0 === e ? this.offsetTime : 0; for (var h = "object" == typeof this.pv ? [this.pv.length] : 0; o;) { if (r = this.keyframes[i], s = this.keyframes[i + 1], i == a - 1 && t >= s.t - e) { r.h && (r = s); break } if (s.t - e > t) break;
                    a - 1 > i ? i += n : o = !1 } var l, p, m, f, c, d = 0; if (r.to) { r.bezierData || bez.buildBezierData(r); var u = r.bezierData; if (t >= s.t - e || t < r.t - e) { var y = t >= s.t - e ? u.points.length - 1 : 0; for (p = u.points[y].point.length, l = 0; p > l; l += 1) h[l] = u.points[y].point[l] } else { r.__fnct ? c = r.__fnct : (c = BezierFactory.getBezierEasing(r.o.x, r.o.y, r.i.x, r.i.y, r.n).get, r.__fnct = c), m = c((t - (r.t - e)) / (s.t - e - (r.t - e))); var g, v = u.segmentLength * m,
                            b = 0; for (n = 1, o = !0, f = u.points.length; o;) { if (b += u.points[d].partialLength * n, 0 === v || 0 === m || d == u.points.length - 1) { for (p = u.points[d].point.length, l = 0; p > l; l += 1) h[l] = u.points[d].point[l]; break } if (v >= b && v < b + u.points[d + 1].partialLength) { for (g = (v - b) / u.points[d + 1].partialLength, p = u.points[d].point.length, l = 0; p > l; l += 1) h[l] = u.points[d].point[l] + (u.points[d + 1].point[l] - u.points[d].point[l]) * g; break }
                            f - 1 > d && 1 == n || d > 0 && -1 == n ? d += n : o = !1 } } } else { var E, P, x, S, C, k = !1; for (a = r.s.length, i = 0; a > i; i += 1) { if (1 !== r.h && (r.o.x instanceof Array ? (k = !0, r.__fnct || (r.__fnct = []), r.__fnct[i] || (E = r.o.x[i] || r.o.x[0], P = r.o.y[i] || r.o.y[0], x = r.i.x[i] || r.i.x[0], S = r.i.y[i] || r.i.y[0])) : (k = !1, r.__fnct || (E = r.o.x, P = r.o.y, x = r.i.x, S = r.i.y)), k ? r.__fnct[i] ? c = r.__fnct[i] : (c = BezierFactory.getBezierEasing(E, P, x, S).get, r.__fnct[i] = c) : r.__fnct ? c = r.__fnct : (c = BezierFactory.getBezierEasing(E, P, x, S).get, r.__fnct = c), m = t >= s.t - e ? 1 : t < r.t - e ? 0 : c((t - (r.t - e)) / (s.t - e - (r.t - e)))), this.sh && 1 !== r.h) { var M = r.s[i],
                                A = r.e[i]; - 180 > M - A ? M += 360 : M - A > 180 && (M -= 360), C = M + (A - M) * m } else C = 1 === r.h ? r.s[i] : r.s[i] + (r.e[i] - r.s[i]) * m;
                        1 === a ? h = C : h[i] = C } } return h }

            function r(t) { if (void 0 !== this.vel) return this.vel; var e, r = -.01,
                    s = this.getValueAtTime(t, 0),
                    i = this.getValueAtTime(t + r, 0); if (s.length) { e = Array.apply(null, { length: s.length }); var a; for (a = 0; a < s.length; a += 1) e[a] = this.elem.globalData.frameRate * ((i[a] - s[a]) / r) } else e = (i - s) / r; return e }

            function s(t) { this.propertyGroup = t }

            function i(t, e, r) { e.x && (r.k = !0, r.x = !0, r.getValue && (r.getPreValue = r.getValue), r.getValue = ExpressionManager.initiateExpression.bind(r)(t, e, r)) } var a = function() {
                    function a(t, e) { return this.textIndex = t + 1, this.textTotal = e, this.getValue(), this.v } return function(n, o) { this.pv = 1, this.comp = n.comp, this.elem = n, this.mult = .01, this.type = "textSelector", this.textTotal = o.totalChars, this.selectorValue = 100, this.lastValue = [1, 1, 1], i.bind(this)(n, o, this), this.getMult = a, this.getVelocityAtTime = r, this.getValueAtTime = this.kf ? e.bind(this) : t.bind(this), this.setGroupProperty = s } }(),
                n = PropertyFactory.getProp;
            PropertyFactory.getProp = function(a, o, h, l, p) { var m = n(a, o, h, l, p);
                m.getVelocityAtTime = r, m.getValueAtTime = m.kf ? e.bind(m) : t.bind(m), m.setGroupProperty = s; var f = m.k; return void 0 !== o.ix && Object.defineProperty(m, "propertyIndex", { get: function() { return o.ix } }), i(a, o, m), !f && m.x && p.push(m), m }; var o = ShapePropertyFactory.getShapeProp;
            ShapePropertyFactory.getShapeProp = function(r, a, n, h, l) { var p = o(r, a, n, h, l);
                p.setGroupProperty = s, p.getValueAtTime = p.kf ? e : t; var m = p.k; return void 0 !== a.ix && Object.defineProperty(p, "propertyIndex", { get: function() { return a.ix } }), 3 === n ? i(r, a.pt, p) : 4 === n && i(r, a.ks, p), !m && p.x && h.push(p), p }; var h = PropertyFactory.getTextSelectorProp;
            PropertyFactory.getTextSelectorProp = function(t, e, r) { return 1 === e.t ? new a(t, e, r) : h(t, e, r) } }();
    var ExpressionManager = function() {
            function duplicatePropertyValue(t, e) { if (e = e || 1, "number" == typeof t || t instanceof Number) return t * e; if (t.i) return JSON.parse(JSON.stringify(t)); var r, s = Array.apply(null, { length: t.length }),
                    i = t.length; for (r = 0; i > r; r += 1) s[r] = t[r] * e; return s }

            function shapesEqual(t, e) { if (t._length !== e._length || t.c !== e.c) return !1; var r, s = t._length; for (r = 0; s > r; r += 1)
                    if (t.v[r][0] !== e.v[r][0] || t.v[r][1] !== e.v[r][1] || t.o[r][0] !== e.o[r][0] || t.o[r][1] !== e.o[r][1] || t.i[r][0] !== e.i[r][0] || t.i[r][1] !== e.i[r][1]) return !1;
                return !0 }

            function $bm_neg(t) { var e = typeof t; if ("number" === e || "boolean" === e || t instanceof Number) return -t; if (t.constructor === Array) { var r, s = t.length,
                        i = []; for (r = 0; s > r; r += 1) i[r] = -t[r]; return i } }

            function sum(t, e) { var r = typeof t,
                    s = typeof e; if ("string" === r || "string" === s) return t + e; if (("number" === r || "boolean" === r || "string" === r || t instanceof Number) && ("number" === s || "boolean" === s || "string" === s || e instanceof Number)) return t + e; if (t.constructor === Array && ("number" === s || "boolean" === s || "string" === s || e instanceof Number)) return t[0] = t[0] + e, t; if (("number" === r || "boolean" === r || "string" === r || t instanceof Number) && e.constructor === Array) return e[0] = t + e[0], e; if (t.constructor === Array && e.constructor === Array) { for (var i = 0, a = t.length, n = e.length, o = []; a > i || n > i;) o[i] = "number" == typeof t[i] && "number" == typeof e[i] ? t[i] + e[i] : void 0 == e[i] ? t[i] : t[i] || e[i], i += 1; return o } return 0 }

            function sub(t, e) { var r = typeof t,
                    s = typeof e; if (("number" === r || "boolean" === r || "string" === r || t instanceof Number) && ("number" === s || "boolean" === s || "string" === s || e instanceof Number)) return t - e; if (t.constructor === Array && ("number" === s || "boolean" === s || "string" === s || e instanceof Number)) return t[0] = t[0] - e, t; if (("number" === r || "boolean" === r || "string" === r || t instanceof Number) && e.constructor === Array) return e[0] = t - e[0], e; if (t.constructor === Array && e.constructor === Array) { for (var i = 0, a = t.length, n = e.length, o = []; a > i || n > i;) o[i] = "number" == typeof t[i] && "number" == typeof e[i] ? t[i] - e[i] : void 0 == e[i] ? t[i] : t[i] || e[i], i += 1; return o } return 0 }

            function mul(t, e) { var r, s = typeof t,
                    i = typeof e; if (("number" === s || "boolean" === s || "string" === s || t instanceof Number) && ("number" === i || "boolean" === i || "string" === i || e instanceof Number)) return t * e; var a, n; if (t.constructor === Array && ("number" === i || "boolean" === i || "string" === i || e instanceof Number)) { for (n = t.length, r = Array.apply(null, { length: n }), a = 0; n > a; a += 1) r[a] = t[a] * e; return r } if (("number" === s || "boolean" === s || "string" === s || t instanceof Number) && e.constructor === Array) { for (n = e.length, r = Array.apply(null, { length: n }), a = 0; n > a; a += 1) r[a] = t * e[a]; return r } return 0 }

            function div(t, e) { var r, s = typeof t,
                    i = typeof e; if (("number" === s || "boolean" === s || "string" === s || t instanceof Number) && ("number" === i || "boolean" === i || "string" === i || e instanceof Number)) return t / e; var a, n; if (t.constructor === Array && ("number" === i || "boolean" === i || "string" === i || e instanceof Number)) { for (n = t.length, r = Array.apply(null, { length: n }), a = 0; n > a; a += 1) r[a] = t[a] / e; return r } if (("number" === s || "boolean" === s || "string" === s || t instanceof Number) && e.constructor === Array) { for (n = e.length, r = Array.apply(null, { length: n }), a = 0; n > a; a += 1) r[a] = t / e[a]; return r } return 0 }

            function clamp(t, e, r) { if (e > r) { var s = r;
                    r = e, e = s } return Math.min(Math.max(t, e), r) }

            function radiansToDegrees(t) { return t / degToRads }

            function degreesToRadians(t) { return t * degToRads }

            function length(t, e) { if ("number" == typeof t) return e = e || 0, Math.abs(t - e);
                e || (e = helperLengthArray); var r, s = Math.min(t.length, e.length),
                    i = 0; for (r = 0; s > r; r += 1) i += Math.pow(e[r] - t[r], 2); return Math.sqrt(i) }

            function normalize(t) { return div(t, length(t)) }

            function rgbToHsl(t) { var e, r, s = t[0],
                    i = t[1],
                    a = t[2],
                    n = Math.max(s, i, a),
                    o = Math.min(s, i, a),
                    h = (n + o) / 2; if (n == o) e = r = 0;
                else { var l = n - o; switch (r = h > .5 ? l / (2 - n - o) : l / (n + o), n) {
                        case s:
                            e = (i - a) / l + (a > i ? 6 : 0); break;
                        case i:
                            e = (a - s) / l + 2; break;
                        case a:
                            e = (s - i) / l + 4 }
                    e /= 6 } return [e, r, h, t[3]] }

            function hslToRgb(t) {
                function e(t, e, r) { return 0 > r && (r += 1), r > 1 && (r -= 1), 1 / 6 > r ? t + 6 * (e - t) * r : .5 > r ? e : 2 / 3 > r ? t + (e - t) * (2 / 3 - r) * 6 : t } var r, s, i, a = t[0],
                    n = t[1],
                    o = t[2]; if (0 == n) r = s = i = o;
                else { var h = .5 > o ? o * (1 + n) : o + n - o * n,
                        l = 2 * o - h;
                    r = e(l, h, a + 1 / 3), s = e(l, h, a), i = e(l, h, a - 1 / 3) } return [r, s, i, t[3]] }

            function linear(t, e, r, s, i) { if (void 0 === s || void 0 === i) return linear(t, 0, 1, e, r); if (e >= t) return s; if (t >= r) return i; var a = r === e ? 0 : (t - e) / (r - e); if (!s.length) return s + (i - s) * a; var n, o = s.length,
                    h = Array.apply(null, { length: o }); for (n = 0; o > n; n += 1) h[n] = s[n] + (i[n] - s[n]) * a; return h }

            function random(t, e) { if (void 0 === e && (void 0 === t ? (t = 0, e = 1) : (e = t, t = void 0)), e.length) { var r, s = e.length;
                    t || (t = Array.apply(null, { length: s })); var i = Array.apply(null, { length: s }),
                        a = BMMath.random(); for (r = 0; s > r; r += 1) i[r] = t[r] + a * (e[r] - t[r]); return i }
                void 0 === t && (t = 0); var n = BMMath.random(); return t + n * (e - t) }

            function initiateExpression(elem, data, property) {
                function lookAt(t, e) { var r = [e[0] - t[0], e[1] - t[1], e[2] - t[2]],
                        s = Math.atan2(r[0], Math.sqrt(r[1] * r[1] + r[2] * r[2])) / degToRads,
                        i = -Math.atan2(r[1], r[2]) / degToRads; return [i, s, 0] }

                function easeOut(t, e, r) { return -(r - e) * t * (t - 2) + e }

                function nearestKey(t) { var e, r, s, i = data.k.length; if (data.k.length && "number" != typeof data.k[0]) { for (r = -1, t *= elem.comp.globalData.frameRate, e = 0; i - 1 > e; e += 1) { if (t === data.k[e].t) { r = e + 1, s = data.k[e].t; break } if (t > data.k[e].t && t < data.k[e + 1].t) { t - data.k[e].t > data.k[e + 1].t - t ? (r = e + 2, s = data.k[e + 1].t) : (r = e + 1, s = data.k[e].t); break } } - 1 === r && (r = e + 1, s = data.k[e].t) } else r = 0, s = 0; var a = {}; return a.index = r, a.time = s / elem.comp.globalData.frameRate, a }

                function key(t) { if (!data.k.length || "number" == typeof data.k[0]) return { time: 0 };
                    t -= 1; var e, r = { time: data.k[t].t / elem.comp.globalData.frameRate };
                    e = t !== data.k.length - 1 || data.k[t].h ? data.k[t].s : data.k[t - 1].e; var s, i = e.length; for (s = 0; i > s; s += 1) r[s] = e[s]; return r }

                function framesToTime(t, e) { return e || (e = elem.comp.globalData.frameRate), t / e }

                function timeToFrames(t, e) { return t || (t = time), e || (e = elem.comp.globalData.frameRate), t * e }

                function toWorld(t) { if (toworldMatrix.reset(), elem.finalTransform.mProp.applyToMatrix(toworldMatrix), elem.hierarchy && elem.hierarchy.length) { var e, r = elem.hierarchy.length; for (e = 0; r > e; e += 1) elem.hierarchy[e].finalTransform.mProp.applyToMatrix(toworldMatrix); return toworldMatrix.applyToPointArray(t[0], t[1], t[2] || 0) } return toworldMatrix.applyToPointArray(t[0], t[1], t[2] || 0) }

                function fromWorld(t) { fromworldMatrix.reset(); var e = []; if (e.push(t), elem.finalTransform.mProp.applyToMatrix(fromworldMatrix), elem.hierarchy && elem.hierarchy.length) { var r, s = elem.hierarchy.length; for (r = 0; s > r; r += 1) elem.hierarchy[r].finalTransform.mProp.applyToMatrix(fromworldMatrix); return fromworldMatrix.inversePoints(e)[0] } return fromworldMatrix.inversePoints(e)[0] }

                function seedRandom(t) { BMMath.seedrandom(randSeed + t) }

                function execute() { if (_needsRandom && seedRandom(randSeed), this.frameExpressionId !== elem.globalData.frameId || "textSelector" === this.type) { if (this.lock) return this.v = duplicatePropertyValue(this.pv, this.mult), !0; "textSelector" === this.type && (textIndex = this.textIndex, textTotal = this.textTotal, selectorValue = this.selectorValue), thisLayer || (thisLayer = elem.layerInterface, thisComp = elem.comp.compInterface), transform || (transform = elem.layerInterface("ADBE Transform Group")), 4 !== elemType || content || (content = thisLayer("ADBE Root Vectors Group")), effect || (effect = thisLayer(4)), hasParent = !(!elem.hierarchy || !elem.hierarchy.length), hasParent && !parent && (parent = elem.hierarchy[elem.hierarchy.length - 1].layerInterface), this.lock = !0, this.getPreValue && this.getPreValue(), value = this.pv, time = this.comp.renderedFrame / this.comp.globalData.frameRate, needsVelocity && (velocity = velocityAtTime(time)), bindedFn(), this.frameExpressionId = elem.globalData.frameId; var t, e; if (this.mult)
                            if ("number" == typeof this.v || this.v instanceof Number || "string" == typeof this.v) this.v *= this.mult;
                            else if (1 === this.v.length) this.v = this.v[0] * this.mult;
                        else
                            for (e = this.v.length, value === this.v && (this.v = 2 === e ? [value[0], value[1]] : [value[0], value[1], value[2]]), t = 0; e > t; t += 1) this.v[t] *= this.mult; if (1 === this.v.length && (this.v = this.v[0]), "number" == typeof this.v || this.v instanceof Number || "string" == typeof this.v) this.lastValue !== this.v && (this.lastValue = this.v, this.mdf = !0);
                        else if (this.v._length) shapesEqual(this.v, this.localShapeCollection.shapes[0]) || (this.mdf = !0, this.localShapeCollection.releaseShapes(), this.localShapeCollection.addShape(shape_pool.clone(this.v)));
                        else
                            for (e = this.v.length, t = 0; e > t; t += 1) this.v[t] !== this.lastValue[t] && (this.lastValue[t] = this.v[t], this.mdf = !0);
                        this.lock = !1 } } var val = data.x,
                    needsVelocity = -1 !== val.indexOf("velocity"),
                    _needsRandom = -1 !== val.indexOf("random"),
                    elemType = elem.data.ty,
                    transform, content, effect, thisComp = elem.comp,
                    thisProperty = property;
                elem.comp.frameDuration = 1 / elem.comp.globalData.frameRate; var inPoint = elem.data.ip / elem.comp.globalData.frameRate,
                    outPoint = elem.data.op / elem.comp.globalData.frameRate,
                    thisLayer, thisComp, fn = new Function,
                    fn = eval("[function(){" + val + ";this.v = $bm_rt;}]")[0],
                    bindedFn = fn.bind(this),
                    numKeys = property.kf ? data.k.length : 0,
                    wiggle = function(t, e) { var r, s, i = this.pv.length ? this.pv.length : 1,
                            a = Array.apply(null, { len: i }); for (s = 0; i > s; s += 1) a[s] = 0;
                        t = 5; var n = Math.floor(time * t); for (r = 0, s = 0; n > r;) { for (s = 0; i > s; s += 1) a[s] += -e + 2 * e * BMMath.random();
                            r += 1 } var o = time * t,
                            h = o - Math.floor(o),
                            l = Array.apply({ length: i }); for (s = 0; i > s; s += 1) l[s] = this.pv[s] + a[s] + (-e + 2 * e * BMMath.random()) * h; return l }.bind(this),
                    loopIn = function(t, e, r) { if (!this.k) return this.pv; var s = time * elem.comp.globalData.frameRate,
                            i = this.keyframes,
                            a = i[0].t; if (s >= a) return this.pv; var n, o;
                        r ? (n = e ? Math.abs(elem.comp.globalData.frameRate * e) : Math.max(0, this.elem.data.op - a), o = a + n) : ((!e || e > i.length - 1) && (e = i.length - 1), o = i[e].t, n = o - a); var h, l, p; if ("pingpong" === t) { var m = Math.floor((a - s) / n); if (m % 2 === 0) return this.getValueAtTime(((a - s) % n + a) / this.comp.globalData.frameRate, 0) } else { if ("offset" === t) { var f = this.getValueAtTime(a / this.comp.globalData.frameRate, 0),
                                    c = this.getValueAtTime(o / this.comp.globalData.frameRate, 0),
                                    d = this.getValueAtTime((n - (a - s) % n + a) / this.comp.globalData.frameRate, 0),
                                    u = Math.floor((a - s) / n) + 1; if (this.pv.length) { for (p = new Array(f.length), l = p.length, h = 0; l > h; h += 1) p[h] = d[h] - (c[h] - f[h]) * u; return p } return d - (c - f) * u } if ("continue" === t) { var y = this.getValueAtTime(a / this.comp.globalData.frameRate, 0),
                                    g = this.getValueAtTime((a + .001) / this.comp.globalData.frameRate, 0); if (this.pv.length) { for (p = new Array(y.length), l = p.length, h = 0; l > h; h += 1) p[h] = y[h] + (y[h] - g[h]) * (a - s) / .001; return p } return y + (y - g) * (a - s) / .001 } } return this.getValueAtTime((n - (a - s) % n + a) / this.comp.globalData.frameRate, 0) }.bind(this),
                    loopInDuration = function(t, e) { return loopIn(t, e, !0) }.bind(this),
                    loopOut = function(t, e, r) { if (!this.k || !this.keyframes) return this.pv; var s = time * elem.comp.globalData.frameRate,
                            i = this.keyframes,
                            a = i[i.length - 1].t; if (a >= s) return this.pv; var n, o;
                        r ? (n = e ? Math.abs(a - elem.comp.globalData.frameRate * e) : Math.max(0, a - this.elem.data.ip), o = a - n) : ((!e || e > i.length - 1) && (e = i.length - 1), o = i[i.length - 1 - e].t, n = a - o); var h, l, p; if ("pingpong" === t) { var m = Math.floor((s - o) / n); if (m % 2 !== 0) return this.getValueAtTime((n - (s - o) % n + o) / this.comp.globalData.frameRate, 0) } else { if ("offset" === t) { var f = this.getValueAtTime(o / this.comp.globalData.frameRate, 0),
                                    c = this.getValueAtTime(a / this.comp.globalData.frameRate, 0),
                                    d = this.getValueAtTime(((s - o) % n + o) / this.comp.globalData.frameRate, 0),
                                    u = Math.floor((s - o) / n); if (this.pv.length) { for (p = new Array(f.length), l = p.length, h = 0; l > h; h += 1) p[h] = (c[h] - f[h]) * u + d[h]; return p } return (c - f) * u + d } if ("continue" === t) { var y = this.getValueAtTime(a / this.comp.globalData.frameRate, 0),
                                    g = this.getValueAtTime((a - .001) / this.comp.globalData.frameRate, 0); if (this.pv.length) { for (p = new Array(y.length), l = p.length, h = 0; l > h; h += 1) p[h] = y[h] + (y[h] - g[h]) * ((s - a) / this.comp.globalData.frameRate) / 5e-4; return p } return y + (y - g) * ((s - a) / .001) } } return this.getValueAtTime(((s - o) % n + o) / this.comp.globalData.frameRate, 0) }.bind(this),
                    loop_out = loopOut,
                    loopOutDuration = function(t, e) { return loopOut(t, e, !0) }.bind(this),
                    valueAtTime = function(t) { return this.getValueAtTime(t, 0) }.bind(this),
                    velocityAtTime = function(t) { return this.getVelocityAtTime(t) }.bind(this),
                    comp = elem.comp.globalData.projectInterface.bind(elem.comp.globalData.projectInterface),
                    toworldMatrix = new Matrix,
                    fromworldMatrix = new Matrix,
                    time, velocity, value, textIndex, textTotal, selectorValue, index = elem.data.ind,
                    hasParent = !(!elem.hierarchy || !elem.hierarchy.length),
                    parent, randSeed = Math.floor(1e6 * Math.random()); return execute } var ob = {},
                Math = BMMath,
                add = sum,
                radians_to_degrees = radiansToDegrees,
                degrees_to_radians = radiansToDegrees,
                helperLengthArray = [0, 0, 0, 0, 0, 0]; return ob.initiateExpression = initiateExpression, ob }(),
        ShapeExpressionInterface = function() {
            function t(t, e, r) { return d(t, e, r) }

            function e(t, e, r) { return y(t, e, r) }

            function r(t, e, r) { return g(t, e, r) }

            function s(t, e, r) { return v(t, e, r) }

            function i(t, e, r) { return b(t, e, r) }

            function a(t, e, r) { return E(t, e, r) }

            function n(t, e, r) { return P(t, e, r) }

            function o(t, e, r) { return x(t, e, r) }

            function h(t, e, r) { return S(t, e, r) }

            function l(t, e, r) { return C(t, e, r) }

            function p(t, e, r) { return k(t, e, r) }

            function m(t, e, r) { return M(t, e, r) }

            function f(t, e, r) { var s, i = [],
                    a = t ? t.length : 0; for (s = 0; a > s; s += 1) "gr" == t[s].ty ? i.push(ShapeExpressionInterface.createGroupInterface(t[s], e[s], r)) : "fl" == t[s].ty ? i.push(ShapeExpressionInterface.createFillInterface(t[s], e[s], r)) : "st" == t[s].ty ? i.push(ShapeExpressionInterface.createStrokeInterface(t[s], e[s], r)) : "tm" == t[s].ty ? i.push(ShapeExpressionInterface.createTrimInterface(t[s], e[s], r)) : "tr" == t[s].ty || ("el" == t[s].ty ? i.push(ShapeExpressionInterface.createEllipseInterface(t[s], e[s], r)) : "sr" == t[s].ty ? i.push(ShapeExpressionInterface.createStarInterface(t[s], e[s], r)) : "sh" == t[s].ty ? i.push(ShapeExpressionInterface.createPathInterface(t[s], e[s], r)) : "rc" == t[s].ty ? i.push(ShapeExpressionInterface.createRectInterface(t[s], e[s], r)) : "rd" == t[s].ty ? i.push(ShapeExpressionInterface.createRoundedInterface(t[s], e[s], r)) : "rp" == t[s].ty && i.push(ShapeExpressionInterface.createRepatearInterface(t[s], e[s], r))); return i }
            var c = { createShapeInterface: t, createGroupInterface: e, createTrimInterface: i, createStrokeInterface: s, createTransformInterface: a, createEllipseInterface: n, createStarInterface: o, createRectInterface: h, createRoundedInterface: l, createRepatearInterface: p, createPathInterface: m, createFillInterface: r },
                d = function() { return function(t, e, r) {
                        function s(t) { if ("number" == typeof t) return i[t - 1]; for (var e = 0, r = i.length; r > e;) { if (i[e]._name === t) return i[e];
                                e += 1 } } var i; return s.propertyGroup = r, i = f(t, e, s), s } }(),
                u = function() { return function(t, e, r) { var s, i = function(t) { for (var e = 0, r = s.length; r > e;) { if (s[e]._name === t || s[e].mn === t || s[e].propertyIndex === t || s[e].ix === t || s[e].ind === t) return s[e];
                                e += 1 } return "number" == typeof t ? s[t - 1] : void 0 }; return i.propertyGroup = function(t) { return 1 === t ? i : r(t - 1) }, s = f(t.it, e.it, i.propertyGroup), i.numProperties = s.length, i.propertyIndex = t.cix, i } }(),
                y = function() {
                    return function(t, e, r) {
                        var s = function(t) { switch (t) {
                                case "ADBE Vectors Group":
                                case "Contents":
                                case 2:
                                    return s.content;
                                case "ADBE Vector Transform Group":
                                case 3:
                                default:
                                    return s.transform } };
                        s.propertyGroup = function(t) { return 1 === t ? s : r(t - 1) };
                        var i = u(t, e, s.propertyGroup),
                            a = ShapeExpressionInterface.createTransformInterface(t.it[t.it.length - 1], e.it[e.it.length - 1], s.propertyGroup);
                        return s.content = i, s.transform = a,
                            Object.defineProperty(s, "_name", { get: function() { return t.nm } }), s.numProperties = t.np, s.propertyIndex = t.ix, s.nm = t.nm, s.mn = t.mn, s
                    }
                }(),
                g = function() { return function(t, e, r) {
                        function s(t) { return "Color" === t || "color" === t ? s.color : "Opacity" === t || "opacity" === t ? s.opacity : void 0 } return Object.defineProperty(s, "color", { get: function() { return ExpressionValue(e.c, 1 / e.c.mult, "color") } }), Object.defineProperty(s, "opacity", { get: function() { return ExpressionValue(e.o, 100) } }), Object.defineProperty(s, "_name", { value: t.nm }), Object.defineProperty(s, "mn", { value: t.mn }), e.c.setGroupProperty(r), e.o.setGroupProperty(r), s } }(),
                v = function() { return function(t, e, r) {
                        function s(t) { return 1 === t ? c : r(t - 1) }

                        function i(t) { return 1 === t ? l : s(t - 1) }

                        function a(r) { Object.defineProperty(l, t.d[r].nm, { get: function() { return ExpressionValue(e.d.dataProps[r].p) } }) }

                        function n(t) { return "Color" === t || "color" === t ? n.color : "Opacity" === t || "opacity" === t ? n.opacity : "Stroke Width" === t || "stroke width" === t ? n.strokeWidth : void 0 } var o, h = t.d ? t.d.length : 0,
                            l = {}; for (o = 0; h > o; o += 1) a(o), e.d.dataProps[o].p.setGroupProperty(i); return Object.defineProperty(n, "color", { get: function() { return ExpressionValue(e.c, 1 / e.c.mult, "color") } }), Object.defineProperty(n, "opacity", { get: function() { return ExpressionValue(e.o, 100) } }), Object.defineProperty(n, "strokeWidth", { get: function() { return ExpressionValue(e.w) } }), Object.defineProperty(n, "dash", { get: function() { return l } }), Object.defineProperty(n, "_name", { value: t.nm }), Object.defineProperty(n, "mn", { value: t.mn }), e.c.setGroupProperty(s), e.o.setGroupProperty(s), e.w.setGroupProperty(s), n } }(),
                b = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return e === t.e.ix || "End" === e || "end" === e ? i.end : e === t.s.ix ? i.start : e === t.o.ix ? i.offset : void 0 } return i.propertyIndex = t.ix, e.s.setGroupProperty(s), e.e.setGroupProperty(s), e.o.setGroupProperty(s), i.propertyIndex = t.ix, Object.defineProperty(i, "start", { get: function() { return ExpressionValue(e.s, 1 / e.s.mult) } }), Object.defineProperty(i, "end", { get: function() { return ExpressionValue(e.e, 1 / e.e.mult) } }), Object.defineProperty(i, "offset", { get: function() { return ExpressionValue(e.o) } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.mn = t.mn, i } }(),
                E = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return t.a.ix === e ? i.anchorPoint : t.o.ix === e ? i.opacity : t.p.ix === e ? i.position : t.r.ix === e ? i.rotation : t.s.ix === e ? i.scale : t.sk && t.sk.ix === e ? i.skew : t.sa && t.sa.ix === e ? i.skewAxis : "Opacity" === e ? i.opacity : "Position" === e ? i.position : "Anchor Point" === e ? i.anchorPoint : "Scale" === e ? i.scale : "Rotation" === e || "ADBE Vector Rotation" === e ? i.rotation : "Skew" === e ? i.skew : "Skew Axis" === e ? i.skewAxis : void 0 }
                        e.transform.mProps.o.setGroupProperty(s), e.transform.mProps.p.setGroupProperty(s), e.transform.mProps.a.setGroupProperty(s), e.transform.mProps.s.setGroupProperty(s), e.transform.mProps.r.setGroupProperty(s), e.transform.mProps.sk && (e.transform.mProps.sk.setGroupProperty(s), e.transform.mProps.sa.setGroupProperty(s)), e.transform.op.setGroupProperty(s), Object.defineProperty(i, "opacity", { get: function() { return ExpressionValue(e.transform.mProps.o, 1 / e.transform.mProps.o.mult) } }), Object.defineProperty(i, "position", { get: function() { return ExpressionValue(e.transform.mProps.p) } }), Object.defineProperty(i, "anchorPoint", { get: function() { return ExpressionValue(e.transform.mProps.a) } }); return Object.defineProperty(i, "scale", { get: function() { return ExpressionValue(e.transform.mProps.s, 1 / e.transform.mProps.s.mult) } }), Object.defineProperty(i, "rotation", { get: function() { return ExpressionValue(e.transform.mProps.r, 1 / e.transform.mProps.r.mult) } }), Object.defineProperty(i, "skew", { get: function() { return ExpressionValue(e.transform.mProps.sk) } }), Object.defineProperty(i, "skewAxis", { get: function() { return ExpressionValue(e.transform.mProps.sa) } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.ty = "tr", i.mn = t.mn, i } }(),
                P = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return t.p.ix === e ? i.position : t.s.ix === e ? i.size : void 0 }
                        i.propertyIndex = t.ix; var a = "tm" === e.sh.ty ? e.sh.prop : e.sh; return a.s.setGroupProperty(s), a.p.setGroupProperty(s), Object.defineProperty(i, "size", { get: function() { return ExpressionValue(a.s) } }), Object.defineProperty(i, "position", { get: function() { return ExpressionValue(a.p) } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.mn = t.mn, i } }(),
                x = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return t.p.ix === e ? i.position : t.r.ix === e ? i.rotation : t.pt.ix === e ? i.points : t.or.ix === e || "ADBE Vector Star Outer Radius" === e ? i.outerRadius : t.os.ix === e ? i.outerRoundness : !t.ir || t.ir.ix !== e && "ADBE Vector Star Inner Radius" !== e ? t.is && t.is.ix === e ? i.innerRoundness : void 0 : i.innerRadius } var a = "tm" === e.sh.ty ? e.sh.prop : e.sh; return i.propertyIndex = t.ix, a.or.setGroupProperty(s), a.os.setGroupProperty(s), a.pt.setGroupProperty(s), a.p.setGroupProperty(s), a.r.setGroupProperty(s), t.ir && (a.ir.setGroupProperty(s), a.is.setGroupProperty(s)), Object.defineProperty(i, "position", { get: function() { return ExpressionValue(a.p) } }), Object.defineProperty(i, "rotation", { get: function() { return ExpressionValue(a.r, 1 / a.r.mult) } }), Object.defineProperty(i, "points", { get: function() { return ExpressionValue(a.pt) } }), Object.defineProperty(i, "outerRadius", { get: function() { return ExpressionValue(a.or) } }), Object.defineProperty(i, "outerRoundness", { get: function() { return ExpressionValue(a.os) } }), Object.defineProperty(i, "innerRadius", { get: function() { return a.ir ? ExpressionValue(a.ir) : 0 } }), Object.defineProperty(i, "innerRoundness", { get: function() { return a.is ? ExpressionValue(a.is, 1 / a.is.mult) : 0 } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.mn = t.mn, i } }(),
                S = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return t.p.ix === e ? i.position : t.r.ix === e ? i.rotation : t.pt.ix === e ? i.points : t.or.ix === e || "ADBE Vector Star Outer Radius" === e ? i.outerRadius : t.os.ix === e ? i.outerRoundness : !t.ir || t.ir.ix !== e && "ADBE Vector Star Inner Radius" !== e ? t.is && t.is.ix === e ? i.innerRoundness : void 0 : i.innerRadius } var a = "tm" === e.sh.ty ? e.sh.prop : e.sh; return i.propertyIndex = t.ix, a.p.setGroupProperty(s), a.s.setGroupProperty(s), a.r.setGroupProperty(s), Object.defineProperty(i, "position", { get: function() { return ExpressionValue(a.p) } }), Object.defineProperty(i, "roundness", { get: function() { return ExpressionValue(a.r) } }), Object.defineProperty(i, "size", { get: function() { return ExpressionValue(a.s) } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.mn = t.mn, i } }(),
                C = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return t.r.ix === e || "Round Corners 1" === e ? i.radius : void 0 } var a = e; return i.propertyIndex = t.ix, a.rd.setGroupProperty(s), Object.defineProperty(i, "radius", { get: function() { return ExpressionValue(a.rd) } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.mn = t.mn, i } }(),
                k = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(e) { return t.c.ix === e || "Copies" === e ? i.copies : t.o.ix === e || "Offset" === e ? i.offset : void 0 } var a = e; return i.propertyIndex = t.ix, a.c.setGroupProperty(s), a.o.setGroupProperty(s), Object.defineProperty(i, "copies", { get: function() { return ExpressionValue(a.c) } }), Object.defineProperty(i, "offset", { get: function() { return ExpressionValue(a.o) } }), Object.defineProperty(i, "_name", { get: function() { return t.nm } }), i.mn = t.mn, i } }(),
                M = function() { return function(t, e, r) {
                        function s(t) { return 1 == t ? i : r(--t) }

                        function i(t) { return "Shape" === t || "shape" === t || "Path" === t || "path" === t ? i.path : void 0 } var a = "tm" === e.sh.ty ? e.sh.prop : e.sh; return a.setGroupProperty(s), Object.defineProperty(i, "path", { get: function() { return a.k && a.getValue(), a.v } }), Object.defineProperty(i, "shape", { get: function() { return a.k && a.getValue(), a.v } }), Object.defineProperty(i, "_name", { value: t.nm }), Object.defineProperty(i, "ix", { value: t.ix }), Object.defineProperty(i, "mn", { value: t.mn }), i } }();
            return c
        }(),
        TextExpressionInterface = function() { return function(t) {
                function e() {} return Object.defineProperty(e, "sourceText", { get: function() { return t.currentTextDocumentData.t ? t.currentTextDocumentData.t : "" } }), e } }(),
        LayerExpressionInterface = function() {
            function t(t) { var e = new Matrix; if (e.reset(), this._elem.finalTransform.mProp.applyToMatrix(e), this._elem.hierarchy && this._elem.hierarchy.length) { var r, s = this._elem.hierarchy.length; for (r = 0; s > r; r += 1) this._elem.hierarchy[r].finalTransform.mProp.applyToMatrix(e); return e.applyToPointArray(t[0], t[1], t[2] || 0) } return e.applyToPointArray(t[0], t[1], t[2] || 0) } return function(e) {
                function r(t) { i.mask = t.getMask.bind(t) }

                function s(t) { i.effect = t }

                function i(t) { switch (t) {
                        case "ADBE Root Vectors Group":
                        case "Contents":
                        case 2:
                            return i.shapeInterface;
                        case 1:
                        case "Transform":
                        case "transform":
                        case "ADBE Transform Group":
                            return a;
                        case 4:
                        case "ADBE Effect Parade":
                            return i.effect } } var a = TransformExpressionInterface(e.transform); return i.toWorld = t, i.toComp = t, i._elem = e, Object.defineProperty(i, "hasParent", { get: function() { return !!e.hierarchy } }), Object.defineProperty(i, "parent", { get: function() { return e.hierarchy[0].layerInterface } }), Object.defineProperty(i, "rotation", { get: function() { return a.rotation } }), Object.defineProperty(i, "scale", { get: function() { return a.scale } }), Object.defineProperty(i, "position", { get: function() { return a.position } }), Object.defineProperty(i, "anchorPoint", { get: function() { return a.anchorPoint } }), Object.defineProperty(i, "transform", { get: function() { return a } }), Object.defineProperty(i, "width", { get: function() { return 0 === e.data.ty ? e.data.w : 100 } }), Object.defineProperty(i, "height", { get: function() { return 0 === e.data.ty ? e.data.h : 100 } }), Object.defineProperty(i, "source", { get: function() { return e.data.refId } }), Object.defineProperty(i, "_name", { value: e.data.nm }), Object.defineProperty(i, "content", { get: function() { return i.shapeInterface } }), Object.defineProperty(i, "active", { get: function() { return e.isVisible } }), Object.defineProperty(i, "text", { get: function() { return i.textInterface } }), i.registerMaskInterface = r, i.registerEffectsInterface = s, i } }(),
        CompExpressionInterface = function() { return function(t) {
                function e(e) { for (var r = 0, s = t.layers.length; s > r;) { if (t.layers[r].nm === e || t.layers[r].ind === e) return t.elements[r].layerInterface;
                        r += 1 } return { active: !1 } } return Object.defineProperty(e, "_name", { value: t.data.nm }), e.layer = e, e.pixelAspect = 1, e.height = t.globalData.compSize.h, e.width = t.globalData.compSize.w, e.pixelAspect = 1, e.frameDuration = 1 / t.globalData.frameRate, e } }(),
        TransformExpressionInterface = function() { return function(t) {
                function e(r) { switch (r) {
                        case "scale":
                        case "Scale":
                        case "ADBE Scale":
                            return e.scale;
                        case "rotation":
                        case "Rotation":
                        case "ADBE Rotation":
                        case "ADBE Rotate Z":
                            return e.rotation;
                        case "position":
                        case "Position":
                        case "ADBE Position":
                            return t.position;
                        case "anchorPoint":
                        case "AnchorPoint":
                        case "Anchor Point":
                        case "ADBE AnchorPoint":
                            return e.anchorPoint;
                        case "opacity":
                        case "Opacity":
                            return e.opacity } } return Object.defineProperty(e, "rotation", { get: function() { return t.rotation } }), Object.defineProperty(e, "scale", { get: function() { return t.scale } }), Object.defineProperty(e, "position", { get: function() { return t.position } }), Object.defineProperty(e, "xPosition", { get: function() { return t.xPosition } }), Object.defineProperty(e, "yPosition", { get: function() { return t.yPosition } }), Object.defineProperty(e, "anchorPoint", { get: function() { return t.anchorPoint } }), Object.defineProperty(e, "opacity", { get: function() { return t.opacity } }), Object.defineProperty(e, "skew", { get: function() { return t.skew } }), Object.defineProperty(e, "skewAxis", { get: function() { return t.skewAxis } }), e } }(),
        ProjectInterface = function() {
            function t(t) { this.compositions.push(t) } return function() {
                function e(t) { for (var e = 0, r = this.compositions.length; r > e;) { if (this.compositions[e].data && this.compositions[e].data.nm === t) return this.compositions[e].prepareFrame(this.currentFrame), this.compositions[e].compInterface;
                        e += 1 } } return e.compositions = [], e.currentFrame = 0, e.registerComposition = t, e } }(),
        EffectsExpressionInterface = function() {
            function t(t, r) { if (t.effects) { var s, i = [],
                        a = t.data.ef,
                        n = t.effects.effectElements.length; for (s = 0; n > s; s += 1) i.push(e(a[s], t.effects.effectElements[s], r, t)); return function(e) { for (var r = t.data.ef, s = 0, a = r.length; a > s;) { if (e === r[s].nm || e === r[s].mn || e === r[s].ix) return i[s];
                            s += 1 } } } }

            function e(t, s, i, a) { var n, o = [],
                    h = t.ef.length; for (n = 0; h > n; n += 1) o.push(5 === t.ef[n].ty ? e(t.ef[n], s.effectElements[n], i, a) : r(s.effectElements[n], t.ef[n].ty, a)); var l = function(e) { for (var r = t.ef, s = 0, i = r.length; i > s;) { if (e === r[s].nm || e === r[s].mn || e === r[s].ix) return 5 === r[s].ty ? o[s] : o[s]();
                        s += 1 } return o[0]() }; return "ADBE Color Control" === t.mn && Object.defineProperty(l, "color", { get: function() { return o[0]() } }), l.active = 0 !== t.en, l }

            function r(t, e, r) { return function() { return 10 === e ? r.comp.compInterface(t.p.v) : ExpressionValue(t.p) } } var s = { createEffectsInterface: t }; return s }(),
        ExpressionValue = function() { return function(t, e, r) { var s;
                t.k && t.getValue(); var i, a, n; if (r) { if ("color" === r) { for (a = 4, s = Array.apply(null, { length: a }), n = Array.apply(null, { length: a }), i = 0; a > i; i += 1) s[i] = n[i] = e && 3 > i ? t.v[i] * e : 1;
                        s.value = n } } else if ("number" == typeof t.v || t.v instanceof Number) s = new Number(e ? t.v * e : t.v), s.value = e ? t.v * e : t.v;
                else { for (a = t.v.length, s = Array.apply(null, { length: a }), n = Array.apply(null, { length: a }), i = 0; a > i; i += 1) s[i] = n[i] = e ? t.v[i] * e : t.v[i];
                    s.value = n } return s.numKeys = t.keyframes ? t.keyframes.length : 0, s.key = function(e) { return s.numKeys ? t.keyframes[e - 1].t : 0 }, s.valueAtTime = t.getValueAtTime, s.propertyGroup = t.propertyGroup, s } }();
    GroupEffect.prototype.getValue = function() { this.mdf = !1; var t, e = this.dynamicProperties.length; for (t = 0; e > t; t += 1) this.dynamicProperties[t].getValue(), this.mdf = this.dynamicProperties[t].mdf ? !0 : this.mdf }, GroupEffect.prototype.init = function(t, e, r) { this.data = t, this.mdf = !1, this.effectElements = []; var s, i, a = this.data.ef.length,
            n = this.data.ef; for (s = 0; a > s; s += 1) switch (n[s].ty) {
            case 0:
                i = new SliderEffect(n[s], e, r), this.effectElements.push(i); break;
            case 1:
                i = new AngleEffect(n[s], e, r), this.effectElements.push(i); break;
            case 2:
                i = new ColorEffect(n[s], e, r), this.effectElements.push(i); break;
            case 3:
                i = new PointEffect(n[s], e, r), this.effectElements.push(i); break;
            case 4:
            case 7:
                i = new CheckboxEffect(n[s], e, r), this.effectElements.push(i); break;
            case 10:
                i = new LayerIndexEffect(n[s], e, r), this.effectElements.push(i); break;
            case 11:
                i = new MaskIndexEffect(n[s], e, r), this.effectElements.push(i); break;
            case 5:
                i = new EffectsManager(n[s], e, r), this.effectElements.push(i); break;
            case 6:
                i = new NoValueEffect(n[s], e, r), this.effectElements.push(i) } };
    var bodymovinjs = {};
    bodymovinjs.play = play, bodymovinjs.pause = pause, bodymovinjs.togglePause = togglePause, bodymovinjs.setSpeed = setSpeed, bodymovinjs.setDirection = setDirection, bodymovinjs.stop = stop, bodymovinjs.moveFrame = moveFrame, bodymovinjs.searchAnimations = searchAnimations, bodymovinjs.registerAnimation = registerAnimation, bodymovinjs.loadAnimation = loadAnimation, bodymovinjs.setSubframeRendering = setSubframeRendering, bodymovinjs.resize = resize, bodymovinjs.start = start, bodymovinjs.goToAndStop = goToAndStop, bodymovinjs.destroy = destroy, bodymovinjs.setQuality = setQuality, bodymovinjs.installPlugin = installPlugin, bodymovinjs.__getFactory = getFactory, bodymovinjs.version = "4.6.3";
    var standalone = "__[STANDALONE]__",
        animationData = "__[ANIMATIONDATA]__",
        renderer = "";
    if (standalone) { var scripts = document.getElementsByTagName("script"),
            index = scripts.length - 1,
            myScript = scripts[index],
            queryString = myScript.src.replace(/^[^\?]+\??/, "");
        renderer = getQueryVariable("renderer") }
    var readyStateCheckInterval = setInterval(checkReady, 100);
    return bodymovinjs
});
