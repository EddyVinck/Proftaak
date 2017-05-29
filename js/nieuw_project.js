var imgCounter = 0;
! function(a, b) {
    "use strict";
    "function" == typeof define && define.amd ? define([], b) : "object" == typeof exports ? module.exports = b() : a.Imgur = b()
}(this, function() {
    "use strict";
    var a = function(b) {
        if (!(this && this instanceof a)) return new a(b);
        if (b || (b = {}), !b.clientid) throw "Provide a valid Client Id here: http://api.imgur.com/";
        this.clientid = b.clientid, this.endpoint = "https://api.imgur.com/3/image", this.callback = b.callback || void 0, this.dropzone = document.querySelectorAll(".dropzone"), this.run()
    };
    return a.prototype = {
        
        createEls: function(a, b, c) {
            var d, e = document.createElement(a);
            for (d in b) b.hasOwnProperty(d) && (e[d] = b[d]);
            return c && e.appendChild(document.createTextNode(c)), e
        },
        insertAfter: function(a, b) {
            a.parentNode.insertBefore(b, a.nextSibling)
        },
        post: function(a, b, c) {
            var d = new XMLHttpRequest;
            d.open("POST", a, !0), d.setRequestHeader("Authorization", "Client-ID " + this.clientid), d.onreadystatechange = function() {
                if (4 === this.readyState) {
                    if (!(this.status >= 200 && this.status < 300)) throw new Error(this.status + " - " + this.statusText);
                    var a = "";
                    try {
                        a = JSON.parse(this.responseText)
                    } catch (b) {
                        a = this.responseText
                    }
                    c.call(window, a)
                }
            }, d.send(b), d = null
        },
        createDragZone: function() {
            var a, b;
            a = this.createEls("p", {id:"pId"}, "Klik om een afbeelding toe te voegen"), b = this.createEls("input", {
                type: "file",
                accept: "image/*"
            }), Array.prototype.forEach.call(this.dropzone, function(c) {
                c.appendChild(a), c.appendChild(b), this.status(c), this.upload(c)
            }.bind(this))
        },
        loading: function() {
            
            var a, b;
            a = this.createEls("div", {
                className: "loading-modal"
            }), b = this.createEls("img", {
                className: "loading-image",
                src: "./svg/loading-spin.svg"
            }), a.appendChild(b), document.body.appendChild(a)
            
        },
        status: function(a) {
            var b = this.createEls("div", {
                className: "status"
            });
            this.insertAfter(a, b)
        },
        matchFiles: function(a, b) {
            var c = b.nextSibling;
            if (a.type.match(/image/) && "image/svg+xml" !== a.type && imgCounter < 4) {
                document.getElementById("pId").innerHTML = "Klik om een afbeelding toe te voegen";
                document.body.classList.add("busy"), c.classList.remove("bg-success", "bg-danger"), c.innerHTML = "";
                var d = new FormData;
                d.append("image", a), this.post(this.endpoint, d, function(a) {
                    document.body.classList.remove("busy"), "function" == typeof this.callback && this.callback.call(this, a)
                }.bind(this))
            } else{
                if (imgCounter >= 4){
                    console.log(document.getElementById("invisImages").getAttribute("src"));
                    
                    document.getElementById("pId").innerHTML = "Je kunt maximaal 4 afbeeldingen invoegen!";
                }
            }
        },
        upload: function(a) {
            if(imgCounter < 4){
            console.log(imgCounter);
            var b, c, d, e, f = ["dragenter", "dragleave", "dragover", "drop"];
            a.addEventListener("change", function(f) {
                if (f.target && "INPUT" === f.target.nodeName && "file" === f.target.type)
                    for (c = f.target.files, d = 0, e = c.length; e > d; d += 1) b = c[d], this.matchFiles(b, a)
            }.bind(this), !1), f.map(function(b) {
                
                a.addEventListener(b, function(a) {
                    a.target && "INPUT" === a.target.nodeName && "file" === a.target.type && ("dragleave" === b || "drop" === b ? a.target.parentNode.classList.remove("dropzone-dragging") : a.target.parentNode.classList.add("dropzone-dragging"))
                }, !1)
            })
            }
        },
        run: function() {
            var a = document.querySelector(".loading-modal");
            a || this.loading(), this.createDragZone()
        }
    }, a
});
var imgsrcCount = 1;
var feedback = function (res) {
    if (res.success === true) {
        document.querySelector('.status').classList.add('bg-success');
        document.getElementById("imgRow").innerHTML+= 
        '<div id="colimg'+imgsrcCount+'" class="col s3">'+
            '<img id="img'+imgsrcCount+'" class="materialboxed" src="'+res.data.link+'">'+
        '</div>';
        document.getElementById("deleteRow").innerHTML+= 
        '<div id="colbtn'+imgsrcCount+'" class="col s3 center">'+
            '<a onclick="deleteImg('+imgsrcCount+');" class="btn-floating btn-medium waves-effect waves-light red"><i class="material-icons">close</i></a>'+
        '</div>';
        $(".materialboxed").removeClass("initialized"); //fix to make all images clickable with materialboxed
        document.getElementById("invisImages").value += ","+res.data.link; //adds the link to invis input
        initImgs(); //initializes all materialboxed images
        imgsrcCount++;
        imgCounter++;
    }
};

new Imgur({
    clientid: '7c68b6afd538ec3',
    callback: feedback
});
function deleteImg(nmr){
    var deleteElem = document.getElementById("img" + nmr);
    var invisElem =  document.getElementById("invisImages");
    var removeString = deleteElem.getAttribute("src");
    var tempval = invisElem.value;
    // console.log("The total string rn: ");
    // console.log(tempval);
    // console.log("The string that needs to be removed from above string:");
    // console.log(removeString);
    invisElem.value = tempval.replace("," + removeString,"");
    // console.log("new string:");
    // console.log(invisElem.value);
    $("#colimg" + nmr).remove();
    $("#colbtn" + nmr).remove();
    imgCounter--;
}
function initImgs(){
    $(document).ready(function(){
        $('.materialboxed').materialbox();
    });
}
function addchbxValue(chbxElem){
    var invisElem = document.getElementById("invisColleges");
    if(chbxElem.checked == true){
        invisElem.value += "," + chbxElem.value;
    }
    else{
        var tempVal = invisElem.value;
        invisElem.value  = tempVal.replace("," + chbxElem.value,"");
    }
}