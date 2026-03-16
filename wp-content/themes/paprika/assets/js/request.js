(function () {
  "use strict";

  function formatRuMobile(value) {
    var digits = String(value || "").replace(/\D/g, "");
    if (!digits) {
      return "";
    }
    if (digits.charAt(0) === "8") {
      digits = "7" + digits.slice(1);
    }
    if (digits.charAt(0) !== "7") {
      digits = "7" + digits;
    }
    digits = digits.slice(0, 11);

    var local = digits.slice(1);
    var p1 = local.slice(0, 3);
    var p2 = local.slice(3, 6);
    var p3 = local.slice(6, 8);
    var p4 = local.slice(8, 10);

    var out = "+7";
    if (p1) {
      out += " (" + p1;
    }
    if (p1.length === 3) {
      out += ")";
    }
    if (p2) {
      out += " " + p2;
    }
    if (p3) {
      out += "-" + p3;
    }
    if (p4) {
      out += "-" + p4;
    }
    return out;
  }

  document.addEventListener("DOMContentLoaded", function () {
    var phoneInput = document.getElementById("request-phone");
    if (!phoneInput) {
      return;
    }

    var applyMask = function () {
      phoneInput.value = formatRuMobile(phoneInput.value);
    };

    phoneInput.addEventListener("input", applyMask);
    phoneInput.addEventListener("blur", applyMask);
    applyMask();
  });
})();
