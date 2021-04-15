function createTesimonialHtml(routeIndex) {
    var returnHtml = ''; 
    returnHtml = '<div>';
    returnHtml += '     <h2>Contractor Testimonials</h2>';
    returnHtml += '     <h3>' +  testimonials['booth' + routeIndex].question + '</h3>';

    for (var i=0; i < testimonials['booth' + routeIndex].testimonials.length; i++) {
        var currentTestimonial = testimonials['booth' + routeIndex].testimonials[i];
        
        returnHtml += '<br><p style=&quot;font-size: 18px&quot;><i>&quot;' +  currentTestimonial.testimonial + '&quot;</i></p>';
        returnHtml += '<p style=&quot;text-align: right;&quot;><span style=&quot;font-size: 20px;&quot;><b>' +  currentTestimonial.name + '</b></span><br>' + currentTestimonial.company + ', ' + currentTestimonial.location + '</p>';
    }
    returnHtml += '</div>';

    return returnHtml;
}